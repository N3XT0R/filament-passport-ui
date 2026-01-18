<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use App\Models\User;
use Filament\Actions\DeleteAction as FilamentDeleteAction;
use Filament\Actions\EditAction;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\CreateClient;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\EditClient;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ViewClient;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;

final class ClientResourcePagesTest extends DatabaseTestCase
{
    public function testCreateClientFlattensScopesAndAssignsGrants(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create(['name' => 'orders']);
        $readAction = PassportScopeActionFactory::new()->withResource($resource)->create(['name' => 'read']);
        $writeAction = PassportScopeActionFactory::new()->withResource($resource)->create(['name' => 'write']);

        Livewire::test(CreateClient::class)
            ->fillForm([
                'name' => 'Scoped Client',
                'grant_type' => 'authorization_code',
                'owner' => $user->getKey(),
                'client_scopes' => [
                    'orders' => [
                        'orders:' . $readAction->name,
                        'orders:' . $readAction->name,
                        'orders:' . $writeAction->name,
                    ],
                ],
                'user_scopes' => [
                    'orders' => [
                        'orders:' . $readAction->name,
                        'orders:' . $readAction->name,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        /** @var Client $client */
        $client = Client::query()->first();
        self::assertNotNull($client);

        $clientGrants = PassportScopeGrant::query()
            ->with(['resource', 'action'])
            ->where('tokenable_type', Client::class)
            ->where('tokenable_id', $client->getKey())
            ->get();

        $clientScopes = $clientGrants->map->toScopeString()->all();
        sort($clientScopes);

        self::assertSame(['orders:read', 'orders:write'], $clientScopes);

        $userGrants = PassportScopeGrant::query()
            ->with(['resource', 'action'])
            ->where('tokenable_type', User::class)
            ->where('tokenable_id', $user->getKey())
            ->where('context_client_id', $client->getKey())
            ->get();

        $userScopes = $userGrants->map->toScopeString()->all();
        sort($userScopes);

        self::assertSame(['orders:read'], $userScopes);
    }

    public function testEditClientUpsertsScopesForClientAndOwner(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', true);

        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create([
            'name' => 'Legacy Client',
            'owner_id' => $user->getKey(),
        ]);

        $resource = PassportScopeResourceFactory::new()->create(['name' => 'billing']);
        $readAction = PassportScopeActionFactory::new()->withResource($resource)->create(['name' => 'read']);
        $writeAction = PassportScopeActionFactory::new()->withResource($resource)->create(['name' => 'write']);

        Livewire::test(EditClient::class, ['record' => $client->getKey()])
            ->fillForm([
                'name' => 'Updated Client',
                'grant_type' => $client->grant_types[0] ?? 'authorization_code',
                'owner' => $user->getKey(),
                'client_scopes' => [
                    'billing' => [
                        'billing:' . $readAction->name,
                        'billing:' . $writeAction->name,
                        'billing:' . $writeAction->name,
                    ],
                ],
                'user_scopes' => [
                    'billing' => [
                        'billing:' . $readAction->name,
                    ],
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $clientGrants = PassportScopeGrant::query()
            ->with(['resource', 'action'])
            ->where('tokenable_type', Client::class)
            ->where('tokenable_id', $client->getKey())
            ->get();

        $clientScopes = $clientGrants->map->toScopeString()->all();
        sort($clientScopes);

        self::assertSame(['billing:read', 'billing:write'], $clientScopes);

        $userGrants = PassportScopeGrant::query()
            ->with(['resource', 'action'])
            ->where('tokenable_type', User::class)
            ->where('tokenable_id', $user->getKey())
            ->where('context_client_id', $client->getKey())
            ->get();

        $userScopes = $userGrants->map->toScopeString()->all();
        sort($userScopes);

        self::assertSame(['billing:read'], $userScopes);
    }

    public function testViewClientHeaderActionsIncludeEditAndDelete(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $component = Livewire::test(ViewClient::class, ['record' => $client->getKey()]);

        $actions = $component->instance()->getHeaderActions();

        self::assertCount(2, $actions);
        self::assertInstanceOf(EditAction::class, $actions[0]);
        self::assertInstanceOf(FilamentDeleteAction::class, $actions[1]);
    }
}
