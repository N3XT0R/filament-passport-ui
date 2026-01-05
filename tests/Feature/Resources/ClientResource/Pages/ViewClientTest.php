<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ViewClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ViewClientTest extends DatabaseTestCase
{
    public function testSecretFromSessionIsInjectedIntoFormData(): void
    {
        config()->set('passport-ui.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $secret = 'plain-secret-from-session';
        session()->put('new_client_secret_' . $client->getKey(), $secret);

        $component = Livewire::test(ViewClient::class, ['record' => $client->getKey()]);

        $component->assertSet('data.secret', $secret);
        $this->assertNull(session()->get('new_client_secret_' . $client->getKey()));
    }
}
