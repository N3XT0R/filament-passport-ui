<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class CachedResourceRepositoryDecoratorTest extends DatabaseTestCase
{
    protected CachedResourceRepositoryDecorator $repository;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->repository = $this->app->make(CachedResourceRepositoryDecorator::class);
    }

    public function testAllIsCached(): void
    {
        PassportScopeResource::factory()->count(2)->create();

        $first = $this->repository->all();

        PassportScopeResource::factory()->create();

        $second = $this->repository->all();

        self::assertInstanceOf(Collection::class, $first);
        self::assertCount(2, $first);
        self::assertSame(
            $first->pluck('id')->all(),
            $second->pluck('id')->all()
        );
    }

    public function testActiveIsCached(): void
    {
        PassportScopeResource::factory()->create(['is_active' => true]);
        PassportScopeResource::factory()->create(['is_active' => false]);

        $first = $this->repository->active();

        PassportScopeResource::factory()->create(['is_active' => true]);

        $second = $this->repository->active();

        self::assertCount(1, $first);
        self::assertCount(1, $second);
    }

    public function testFindByNameIsCached(): void
    {
        PassportScopeResource::factory()->create([
            'name' => 'users',
        ]);

        $first = $this->repository->findByName('users');

        PassportScopeResource::where('name', 'users')->delete();

        $second = $this->repository->findByName('users');

        self::assertNotNull($first);
        self::assertNotNull($second);
        self::assertSame($first->getKey(), $second->getKey());
    }

    public function testIsMigratedDelegatesToInnerRepository(): void
    {
        self::assertTrue(
            $this->repository->isMigrated()
        );
    }

    public function testCacheIsInvalidatedWhenFlushed(): void
    {
        PassportScopeResource::factory()->count(2)->create();

        self::assertCount(2, $this->repository->all());

        Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.resources',
        ])->flush();

        PassportScopeResource::factory()->create();

        self::assertCount(3, $this->repository->all());
    }

    public function testCreateResourceClearsCache(): void
    {
        PassportScopeResource::factory()->create();

        self::assertCount(1, $this->repository->all());

        $this->repository->createResource(
            PassportScopeResource::factory()->raw([
                'name' => 'projects',
            ])
        );

        self::assertCount(2, $this->repository->all());
    }

    public function testUpdateResourceClearsCache(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'users',
        ]);

        self::assertSame('users', $this->repository->findByName('users')?->name);

        $this->repository->updateResource($resource, [
            'name' => 'accounts',
        ]);

        self::assertNull($this->repository->findByName('users'));
        self::assertSame('accounts', $this->repository->findByName('accounts')?->name);
    }

    public function testDeleteResourceClearsCache(): void
    {
        [$first, $second] = PassportScopeResource::factory()->count(2)->create();

        self::assertCount(2, $this->repository->all());

        $this->repository->deleteResource($first);

        $result = $this->repository->all();

        self::assertCount(1, $result);
        self::assertFalse($result->contains('id', $first->id));
        self::assertTrue($result->contains('id', $second->id));
    }
}
