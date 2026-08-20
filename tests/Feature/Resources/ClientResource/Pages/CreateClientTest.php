<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\CreateClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class CreateClientTest extends DatabaseTestCase
{
    public function testClientCanBeCreatedAndSecretIsStoredInSession(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

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

    public function testScopeResourcesAndActionsAreShownInForm(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $user = User::factory()->create();
        $this->actingAs($user);

        $ordersResource = PassportScopeResourceFactory::new()->create([
            'name' => 'orders',
        ]);
        $paymentsResource = PassportScopeResourceFactory::new()->create([
            'name' => 'payments',
        ]);

        $ordersAction = PassportScopeActionFactory::new()->withResource($ordersResource)->create([
            'name' => 'read',
        ]);
        $globalAction = PassportScopeActionFactory::new()->create([
            'name' => 'approve',
        ]);

        $component = Livewire::test(CreateClient::class);

        $component->assertSeeText($ordersResource->name);
        $component->assertSeeText($paymentsResource->name);

        $component->assertSeeText($ordersResource->name . ':' . $ordersAction->name);
        $component->assertSeeText($paymentsResource->name . ':' . $globalAction->name);
    }

    public function testSelfServiceModeForcesOwnerToCurrentUserRegardlessOfSubmittedData(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $panel = \Filament\Facades\Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        \Filament\Facades\Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        Livewire::test(CreateClient::class)
            ->fillForm([
                'name' => 'My Self-Service Client',
                'grant_type' => 'personal_access',
                'owner' => $otherUser->getKey(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $client = \N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client::where('name', 'My Self-Service Client')->firstOrFail();

        $this->assertSame($owner->getKey(), $client->owner_id);
    }
}
