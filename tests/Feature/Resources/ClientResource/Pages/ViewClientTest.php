<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ViewClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ViewClientTest extends DatabaseTestCase
{
    public function testSecretFromSessionIsInjectedIntoFormData(): void
    {
        $this->markTestSkipped('Livewire error bag initialization issue under PHP 8.4.');

        config()->set('passport-ui.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        session()->start();
        $errorBag = new ViewErrorBag();
        $errorBag->put('default', new MessageBag());
        session()->put('errors', $errorBag);
        session()->flash('errors', $errorBag);
        $_SESSION['errors'] = $errorBag;
        session()->save();

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $secret = 'plain-secret-from-session';
        session()->put('new_client_secret_' . $client->getKey(), $secret);

        $component = Livewire::test(ViewClient::class, ['record' => $client->getKey()]);

        $component->assertSet('data.secret', $secret);
        $this->assertNull(session()->get('new_client_secret_' . $client->getKey()));
    }
}
