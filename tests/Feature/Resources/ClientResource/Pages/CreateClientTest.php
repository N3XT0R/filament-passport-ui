<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\CreateClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class CreateClientTest extends DatabaseTestCase
{
    public function testClientCanBeCreatedAndSecretIsStoredInSession(): void
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

        $livewire = Livewire::test(CreateClient::class);

        $livewire->fillForm([
            'name' => 'New Client',
            'grant_type' => 'authorization_code',
            'owner' => $user->getKey(),
        ])->call('create')
            ->assertHasNoFormErrors();

        $client = Client::query()->first();

        $this->assertNotNull($client);
        $this->assertSame('New Client', $client->name);
        $this->assertNotNull(session()->get('new_client_secret_' . $client->getKey()));
    }
}
