<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class ResourceRepositoryTest extends DatabaseTestCase
{
    protected ResourceRepository $resourceRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceRepository = $this->app->make(ResourceRepository::class);
    }

    public function testAllReturnsAllResources(): void
    {
        PassportScopeResource::factory()->count(3)->create();

        $resources = $this->resourceRepository->all();

        self::assertInstanceOf(Collection::class, $resources);
        self::assertCount(3, $resources);
        self::assertContainsOnlyInstancesOf(PassportScopeResource::class, $resources);
    }

    public function testActiveReturnsOnlyActiveResources(): void
    {
        PassportScopeResource::factory()->create(['is_active' => true]);
        PassportScopeResource::factory()->create(['is_active' => true]);
        PassportScopeResource::factory()->create(['is_active' => false]);

        $active = $this->resourceRepository->active();

        self::assertCount(2, $active);
        self::assertTrue(
            $active->every(
                static fn(PassportScopeResource $resource) => $resource->is_active === true
            )
        );
    }

    public function testCountReturnsTotalNumberOfResources(): void
    {
        PassportScopeResource::factory()->count(4)->create();

        self::assertSame(
            4,
            $this->resourceRepository->count()
        );
    }

    public function testFindByNameReturnsResource(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'users',
        ]);

        $found = $this->resourceRepository->findByName('users');

        self::assertNotNull($found);
        self::assertSame($resource->getKey(), $found->getKey());
    }

    public function testFindByNameReturnsNullWhenNotFound(): void
    {
        self::assertNull(
            $this->resourceRepository->findByName('non-existing')
        );
    }

    public function testIsMigratedReturnsTrueWhenTableExists(): void
    {
        self::assertTrue(
            $this->resourceRepository->isMigrated()
        );
    }
}
