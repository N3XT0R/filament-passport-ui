<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ViewClient;
use N3XT0R\FilamentPassportUi\Support\Cache\CacheFlasher;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class ViewClientTest extends DatabaseTestCase
{
    public function testSecretFromSessionIsInjectedIntoFormData(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $secret = 'plain-secret-from-session';
        CacheFlasher::put('passport.client.secret', $client->getKey(), $secret);
        session()->put('new_client_secret_' . $client->getKey(), $secret);

        $component = Livewire::test(ViewClient::class, ['record' => $client->getKey()]);

        $component->assertSet('data.secret', $secret);
        $this->assertNull(CacheFlasher::pull('passport.client.secret', $client->getKey()));
    }
}
