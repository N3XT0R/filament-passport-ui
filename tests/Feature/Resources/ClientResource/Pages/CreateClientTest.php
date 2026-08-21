<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\CreateClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

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

    public function testSelfServiceModeOnlyOffersActingUsersOwnScopesInClientStepCheckboxList(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        $paymentsResource = PassportScopeResourceFactory::new()->create(['name' => 'payments']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);
        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'write']);
        PassportScopeActionFactory::new()->withResource($paymentsResource)->create(['name' => 'view']);

        $owner = User::factory()->create();
        app(GrantService::class)->grantScopeToTokenable($owner, 'orders', 'read');

        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $component = Livewire::test(CreateClient::class);

        $component->assertSeeText('orders:read');
        $component->assertDontSeeText('orders:write');
        $component->assertDontSeeText('payments:view');
    }

    public function testSelfServiceModeWithNoScopeGrantsOffersNoSelectableScopesInClientStepCheckboxList(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        $paymentsResource = PassportScopeResourceFactory::new()->create(['name' => 'payments']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);
        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'write']);
        PassportScopeActionFactory::new()->withResource($paymentsResource)->create(['name' => 'view']);

        // A freshly onboarded self-service user: zero scope grants of their own.
        $owner = User::factory()->create();

        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $component = Livewire::test(CreateClient::class);

        $component->assertDontSeeText('orders:read');
        $component->assertDontSeeText('orders:write');
        $component->assertDontSeeText('payments:view');
    }

    public function testAdminModeOffersAllScopesInClientStepCheckboxListRegardlessOfActingUsersGrants(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);
        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'write']);

        // The acting admin has no scope grants of their own at all.
        $admin = User::factory()->create();
        $this->actingAs($admin, 'web');

        $component = Livewire::test(CreateClient::class);

        $component->assertSeeText('orders:read');
        $component->assertSeeText('orders:write');
    }

    public function testSelfServiceModeFiltersOutUngrantedScopesFromSubmittedClientAndUserScopes(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        $paymentsResource = PassportScopeResourceFactory::new()->create(['name' => 'payments']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);
        PassportScopeActionFactory::new()->withResource($paymentsResource)->create(['name' => 'write']);

        $owner = User::factory()->create();
        app(GrantService::class)->grantScopeToTokenable($owner, 'orders', 'read');

        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        // Simulate a tampered/bypassed request: both the client-level and
        // user-level scope submissions contain a scope ("payments:write")
        // the acting user was never granted themselves, alongside one they
        // do hold ("orders:read"). This bypasses the UI-level `allowed`
        // restriction entirely (fillForm sets Livewire state directly),
        // exercising the actual server-side security boundary.
        Livewire::test(CreateClient::class)
            ->fillForm([
                'name' => 'Tampered Scope Client',
                'grant_type' => 'personal_access',
                'owner' => $owner->getKey(),
                'client_scopes' => [
                    'orders' => ['orders:read'],
                    'payments' => ['payments:write'],
                ],
                'user_scopes' => [
                    'orders' => ['orders:read'],
                    'payments' => ['payments:write'],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $client = Client::where('name', 'Tampered Scope Client')->firstOrFail();

        $grantService = app(GrantService::class);

        $clientOwnScopes = $grantService->getTokenableGrantsAsScopes($client, $client)->all();
        $this->assertContains('orders:read', $clientOwnScopes);
        $this->assertNotContains('payments:write', $clientOwnScopes);

        $userScopesForClient = $grantService->getTokenableGrantsAsScopes($owner, $client)->all();
        $this->assertContains('orders:read', $userScopesForClient);
        $this->assertNotContains('payments:write', $userScopesForClient);
    }

    public function testAdminModeAllowsGrantingAnyScopeRegardlessOfActingUsersOwnGrants(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);

        $admin = User::factory()->create();
        $owner = User::factory()->create();
        $this->actingAs($admin, 'web');

        Livewire::test(CreateClient::class)
            ->fillForm([
                'name' => 'Admin Granted Client',
                'grant_type' => 'personal_access',
                'owner' => $owner->getKey(),
                'client_scopes' => [
                    'orders' => ['orders:read'],
                ],
                'user_scopes' => [
                    'orders' => ['orders:read'],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $client = Client::where('name', 'Admin Granted Client')->firstOrFail();

        $grantService = app(GrantService::class);
        $userScopesForClient = $grantService->getTokenableGrantsAsScopes($owner, $client)->all();

        $this->assertContains('orders:read', $userScopesForClient);
    }
}
