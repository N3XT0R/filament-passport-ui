<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Services;

use App\Models\User;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Services\GrantService;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class GrantServiceTest extends DatabaseTestCase
{
    protected GrantService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(GrantService::class);
    }

    public function testGrantScopeToTokenable(): void
    {
        $client = Client::factory()->create();

        $resource = PassportScopeResource::factory()->create(['name' => 'users']);
        $action = PassportScopeAction::factory()->create(['name' => 'read']);

        $grant = $this->service->grantScopeToTokenable(
            $client,
            'users',
            'read'
        );

        self::assertInstanceOf(PassportScopeGrant::class, $grant);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $client->getMorphClass(),
            'tokenable_id' => $client->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);
    }

    public function testGrantScopeLogsActivityWithActor(): void
    {
        $actor = User::factory()->create();
        $client = Client::factory()->create();

        PassportScopeResource::factory()->create(['name' => 'users']);
        PassportScopeAction::factory()->create(['name' => 'update']);

        $this->service->grantScopeToTokenable(
            $client,
            'users',
            'update',
            $actor
        );

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope grant given to tokenable',
        ]);
    }

    public function testRevokeScopeFromTokenable(): void
    {
        $client = Client::factory()->create();

        $resource = PassportScopeResource::factory()->create(['name' => 'users']);
        $action = PassportScopeAction::factory()->create(['name' => 'delete']);

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create([
                'resource_id' => $resource->getKey(),
                'action_id' => $action->getKey(),
            ]);

        $result = $this->service->revokeScopeFromTokenable(
            $client,
            'users',
            'delete'
        );

        self::assertTrue($result);

        $this->assertDatabaseMissing('passport_scope_grants', [
            'tokenable_id' => $client->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);
    }

    public function testTokenableHasGrant(): void
    {
        $client = Client::factory()->create();

        $resource = PassportScopeResource::factory()->create(['name' => 'projects']);
        $action = PassportScopeAction::factory()->create(['name' => 'read']);

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create([
                'resource_id' => $resource->getKey(),
                'action_id' => $action->getKey(),
            ]);

        self::assertTrue(
            $this->service->tokenableHasGrant($client, 'projects', 'read')
        );

        self::assertFalse(
            $this->service->tokenableHasGrant($client, 'projects', 'write')
        );
    }

    public function testTokenableHasGrantToScope(): void
    {
        $client = Client::factory()->create();

        PassportScopeResource::factory()->create(['name' => 'users']);
        PassportScopeAction::factory()->create(['name' => 'read']);

        $this->service->grantScopeToTokenable($client, 'users', 'read');

        self::assertTrue(
            $this->service->tokenableHasGrantToScope($client, 'users:read')
        );
    }

    public function testGetTokenableGrantsAsScopes(): void
    {
        $client = Client::factory()->create();

        PassportScopeResource::factory()->create(['name' => 'users']);
        PassportScopeAction::factory()->create(['name' => 'read']);
        PassportScopeAction::factory()->create(['name' => 'update']);

        $this->service->grantScopeToTokenable($client, 'users', 'read');
        $this->service->grantScopeToTokenable($client, 'users', 'update');

        $scopes = $this->service->getTokenableGrantsAsScopes($client)->toArray();

        self::assertEqualsCanonicalizing(
            ['users:read', 'users:update'],
            $scopes
        );
    }

    public function testGiveGrantsToTokenableSkipsExistingGrants(): void
    {
        $client = Client::factory()->create();

        PassportScopeResource::factory()->create(['name' => 'users']);
        PassportScopeAction::factory()->create(['name' => 'read']);
        PassportScopeAction::factory()->create(['name' => 'update']);

        $this->service->grantScopeToTokenable($client, 'users', 'read');

        $this->service->giveGrantsToTokenable(
            $client,
            ['users:read', 'users:update']
        );

        self::assertCount(
            2,
            PassportScopeGrant::where('tokenable_id', $client->getKey())->get()
        );
    }

    public function testUpsertGrantsForTokenable(): void
    {
        $client = Client::factory()->create();

        PassportScopeResource::factory()->create(['name' => 'users']);
        PassportScopeAction::factory()->create(['name' => 'read']);
        PassportScopeAction::factory()->create(['name' => 'update']);

        $this->service->grantScopeToTokenable($client, 'users', 'read');

        $this->service->upsertGrantsForTokenable(
            $client,
            ['users:update']
        );

        $scopes = $this->service->getTokenableGrantsAsScopes($client)->toArray();

        self::assertSame(['users:update'], $scopes);
    }

    public function testBulkGrantLogsActivityWithActor(): void
    {
        $actor = User::factory()->create();
        $client = Client::factory()->create();

        PassportScopeResource::factory()->create(['name' => 'users']);
        PassportScopeAction::factory()->create(['name' => 'read']);

        $this->service->giveGrantsToTokenable(
            $client,
            ['users:read'],
            $actor
        );

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope grants given to tokenable',
        ]);
    }
}
