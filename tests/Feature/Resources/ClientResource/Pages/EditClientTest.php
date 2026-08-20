<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\EditClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

class EditClientTest extends DatabaseTestCase
{
    public function testClientCanBeUpdated(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

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
    }

    public function testSelfServiceModeRestrictsClientScopeOptionsToActingUsersOwnGrantsOnEditPage(): void
    {
        // Confirms ClientWizardForm's `allowed` fix (Finding 1) is shared
        // between CreateClient and EditClient (EditClient does not override
        // form(), so it inherits ClientResource::form() ->
        // ClientWizardForm::configure()), and therefore covers the edit
        // page for free without a separate fix.
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        $paymentsResource = PassportScopeResourceFactory::new()->create(['name' => 'payments']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);
        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'write']);
        PassportScopeActionFactory::new()->withResource($paymentsResource)->create(['name' => 'view']);

        $owner = User::factory()->create();
        app(GrantService::class)->grantScopeToTokenable($owner, 'orders', 'read');

        // grant_type is deliberately client_credentials (no owner/user
        // permission involved) so only the "user_permission" wizard step
        // stays hidden, isolating the client-step checkbox list (the one
        // Finding 1's fix restricts) from the separately-allowed
        // user-permission step's checkbox list.
        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass(),
            'grant_types' => ['client_credentials'],
        ]);

        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $component = Livewire::test(EditClient::class, ['record' => $client->getKey()]);

        $component->assertSeeText('orders:read');
        $component->assertDontSeeText('orders:write');
        $component->assertDontSeeText('payments:view');
    }

    public function testSelfServiceModeForcesOwnerAndActuallyUpsertsGrantedScopesOnEdit(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        app(GrantService::class)->grantScopeToTokenable($owner, 'orders', 'read');

        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass(),
        ]);

        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        // Prior to the fix, `owner` is disabled + not dehydrated on the edit
        // form in self-service mode, so `data['owner']` was empty and
        // UpsertGrantsForTokenableUseCase silently never ran (success toast,
        // no actual change). This also attempts to tamper the owner via a
        // bypassed request; it must be ignored and forced server-side.
        Livewire::test(EditClient::class, ['record' => $client->getKey()])
            ->fillForm([
                'name' => $client->name,
                'grant_type' => $client->grant_types[0] ?? 'authorization_code',
                'owner' => $otherUser->getKey(),
                'user_scopes' => [
                    'orders' => ['orders:read'],
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $client->refresh();

        $this->assertSame($owner->getKey(), $client->owner_id);

        $grantedScopes = app(GrantService::class)->getTokenableGrantsAsScopes($owner, $client)->all();
        $this->assertContains('orders:read', $grantedScopes);
    }

    public function testSelfServiceModeFiltersOutUngrantedScopesOnEdit(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        $paymentsResource = PassportScopeResourceFactory::new()->create(['name' => 'payments']);

        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);
        PassportScopeActionFactory::new()->withResource($paymentsResource)->create(['name' => 'write']);

        $owner = User::factory()->create();
        app(GrantService::class)->grantScopeToTokenable($owner, 'orders', 'read');

        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass(),
        ]);

        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        // Bypassed request: submits both a scope the owner holds
        // ("orders:read") and one they were never granted
        // ("payments:write"). Only the granted one may end up persisted.
        Livewire::test(EditClient::class, ['record' => $client->getKey()])
            ->fillForm([
                'name' => $client->name,
                'grant_type' => $client->grant_types[0] ?? 'authorization_code',
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
            ->call('save')
            ->assertHasNoFormErrors();

        $client->refresh();

        $grantService = app(GrantService::class);

        $clientOwnScopes = $grantService->getTokenableGrantsAsScopes($client, $client)->all();
        $this->assertContains('orders:read', $clientOwnScopes);
        $this->assertNotContains('payments:write', $clientOwnScopes);

        $userScopesForClient = $grantService->getTokenableGrantsAsScopes($owner, $client)->all();
        $this->assertContains('orders:read', $userScopesForClient);
        $this->assertNotContains('payments:write', $userScopesForClient);
    }

    public function testAdminModeEditRetainsExplicitlySubmittedOwnerAndAllowsAnyScope(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $ordersResource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        PassportScopeActionFactory::new()->withResource($ordersResource)->create(['name' => 'read']);

        $admin = User::factory()->create();
        $owner = User::factory()->create();

        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass(),
        ]);

        $this->actingAs($admin, 'web');

        Livewire::test(EditClient::class, ['record' => $client->getKey()])
            ->fillForm([
                'name' => $client->name,
                'grant_type' => $client->grant_types[0] ?? 'authorization_code',
                'owner' => $owner->getKey(),
                'user_scopes' => [
                    'orders' => ['orders:read'],
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $client->refresh();

        $this->assertSame($owner->getKey(), $client->owner_id);

        $grantedScopes = app(GrantService::class)->getTokenableGrantsAsScopes($owner, $client)->all();
        $this->assertContains('orders:read', $grantedScopes);
    }
}
