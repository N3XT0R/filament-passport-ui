<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

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
}
