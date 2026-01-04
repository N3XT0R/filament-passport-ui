<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Services\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ScopeRegistryServiceTest extends DatabaseTestCase
{
    protected ScopeRegistryService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ScopeRegistryService::class);
    }

    public function testAllReturnsAllActiveScopes(): void
    {
        $users = PassportScopeResource::factory()->create([
            'name' => 'users',
            'description' => 'User management',
            'is_active' => true,
        ]);

        PassportScopeAction::factory()->create([
            'name' => 'read',
            'description' => 'Read users',
            'resource_id' => $users->getKey(),
            'is_active' => true,
        ]);

        PassportScopeAction::factory()->create([
            'name' => 'update',
            'description' => 'Update users',
            'resource_id' => $users->getKey(),
            'is_active' => true,
        ]);

        $scopes = $this->service->all();

        self::assertInstanceOf(Collection::class, $scopes);

        self::assertSame(
            [
                'users:read' => 'User management: Read users',
                'users:update' => 'User management: Update users',
            ],
            $scopes->toArray()
        );
    }

    public function testAllIncludesGlobalActions(): void
    {
        PassportScopeResource::factory()->create([
            'name' => 'users',
            'is_active' => true,
        ]);

        PassportScopeAction::factory()->create([
            'name' => 'read',
            'resource_id' => null,
            'is_active' => true,
        ]);

        $scopes = $this->service->all();

        self::assertArrayHasKey('users:read', $scopes->toArray());
    }

    public function testAllScopeNamesReturnsDtos(): void
    {
        $projects = PassportScopeResource::factory()->create([
            'name' => 'projects',
            'is_active' => true,
        ]);

        PassportScopeAction::factory()->create([
            'name' => 'read',
            'description' => 'Read projects',
            'resource_id' => $projects->getKey(),
            'is_active' => true,
        ]);

        $result = $this->service->allScopeNames();

        self::assertInstanceOf(Collection::class, $result);
        self::assertCount(1, $result);

        $dto = $result->first();

        self::assertInstanceOf(ScopeDTO::class, $dto);
        self::assertSame('projects:read', $dto->scope);
        self::assertSame('projects', $dto->resource);
        self::assertFalse($dto->isGlobal);
        self::assertSame('Read projects', $dto->description);
    }

    public function testAllScopeNamesMarksGlobalActions(): void
    {
        PassportScopeResource::factory()->create([
            'name' => 'users',
            'is_active' => true,
        ]);

        PassportScopeAction::factory()->create([
            'name' => 'export',
            'description' => 'Export data',
            'resource_id' => null,
            'is_active' => true,
        ]);

        $dto = $this->service->allScopeNames()->first();

        self::assertTrue($dto->isGlobal);
        self::assertSame('users:export', $dto->scope);
    }

    public function testIsMigratedReturnsTrueWhenTablesExist(): void
    {
        self::assertTrue(
            $this->service->isMigrated()
        );
    }

    public function testClearCacheDoesNotFail(): void
    {
        $this->service->clearCache();

        self::assertTrue(true);
    }
}
