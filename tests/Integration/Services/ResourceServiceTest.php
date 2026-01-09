<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Services;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Services\ResourceService;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class ResourceServiceTest extends DatabaseTestCase
{
    protected ResourceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ResourceService::class);
    }

    public function testCreateResourcePersistsAndLogsWithActor(): void
    {
        $actor = User::factory()->create();

        $resource = $this->service->createResource([
            'name' => 'projects',
            'description' => 'Manage projects',
            'is_active' => true,
        ], $actor);

        self::assertInstanceOf(PassportScopeResource::class, $resource);
        $this->assertDatabaseHas('passport_scope_resources', [
            'id' => $resource->getKey(),
            'name' => 'projects',
            'description' => 'Manage projects',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth_scope_resource',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope resource created',
        ]);
    }

    public function testUpdateResourceUpdatesAttributesAndLogs(): void
    {
        $actor = User::factory()->create();
        $resource = PassportScopeResource::factory()->create([
            'name' => 'users',
            'description' => 'User resource',
            'is_active' => true,
        ]);

        $updated = $this->service->updateResource($resource, [
            'name' => 'customers',
            'description' => 'Customer resource',
            'is_active' => false,
        ], $actor);

        self::assertSame('customers', $updated->getAttribute('name'));
        self::assertSame('Customer resource', $updated->getAttribute('description'));
        self::assertFalse($updated->getAttribute('is_active'));

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth_scope_resource',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope resource updated',
        ]);
    }

    public function testDeleteResourceRemovesRecordAndLogsWithActor(): void
    {
        $actor = User::factory()->create();
        $resource = PassportScopeResource::factory()->create([
            'name' => 'archives',
            'description' => 'Archive data',
        ]);

        $result = $this->service->deleteResource($resource, $actor);

        self::assertTrue($result);
        $this->assertDatabaseMissing('passport_scope_resources', [
            'id' => $resource->getKey(),
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth_scope_resource',
            'causer_id' => $actor->getKey(),
            'description' => 'OAuth scope resource deleted',
        ]);
    }
}
