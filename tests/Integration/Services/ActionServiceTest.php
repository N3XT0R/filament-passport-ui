<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Services;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Services\ActionService;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class ActionServiceTest extends DatabaseTestCase
{
    protected ActionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ActionService::class);
    }

    public function testCreateActionPersistsAndLogsWithActor(): void
    {
        $actor = User::factory()->create();
        $resource = PassportScopeResource::factory()->create();

        $action = $this->service->createAction([
            'name' => 'export',
            'description' => 'Export records',
            'resource_id' => $resource->getKey(),
            'is_active' => true,
        ], $actor);

        self::assertInstanceOf(PassportScopeAction::class, $action);
        self::assertDatabaseHas('passport_scope_actions', [
            'id' => $action->getKey(),
            'name' => 'export',
            'resource_id' => $resource->getKey(),
            'description' => 'Export records',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth_scope_action',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope action created',
        ]);
    }

    public function testUpdateActionChangesAttributesAndLogs(): void
    {
        $actor = User::factory()->create();
        $action = PassportScopeAction::factory()
            ->withResource()
            ->create([
                'name' => 'read',
                'description' => 'Read data',
                'is_active' => true,
            ]);

        $updated = $this->service->updateAction($action, [
            'name' => 'update',
            'description' => 'Update data',
            'is_active' => false,
        ], $actor);

        self::assertSame('update', $updated->getAttribute('name'));
        self::assertSame('Update data', $updated->getAttribute('description'));
        self::assertFalse($updated->getAttribute('is_active'));

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth_scope_action',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope action updated',
        ]);
    }

    public function testDeleteActionRemovesRecordAndLogsWithActor(): void
    {
        $actor = User::factory()->create();
        $action = PassportScopeAction::factory()->withResource()->create([
            'name' => 'delete',
            'description' => 'Delete data',
            'is_active' => true,
        ]);

        $result = $this->service->deleteAction($action, $actor);

        self::assertTrue($result);
        $this->assertDatabaseMissing('passport_scope_actions', [
            'id' => $action->getKey(),
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth_scope_action',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope action deleted',
        ]);
    }
}
