<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\EditClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class EditClientTest extends DatabaseTestCase
{
    public function testClientCanBeUpdated(): void
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
        $client = ClientFactory::new()->create([
            'name' => 'Old Client',
        ]);

        Livewire::test(EditClient::class, ['record' => $client->getKey()])
            ->fillForm([
                'name' => 'Updated Client',
                'grant_type' => $client->grant_types[0] ?? 'authorization_code',
                'owner' => $user->getKey(),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $client->refresh();

        $this->assertSame('Updated Client', $client->name);
        $this->assertSame($user->getKey(), $client->user_id);
    }
}
