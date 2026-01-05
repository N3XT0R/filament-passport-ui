<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\CreateClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class CreateClientTest extends DatabaseTestCase
{
    public function testClientCanBeCreatedAndSecretIsStoredInSession(): void
    {
        config()->set('passport-ui.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

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
