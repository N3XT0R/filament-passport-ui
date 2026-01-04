<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class CachedActionRepositoryDecoratorTest extends DatabaseTestCase
{
    protected CachedActionRepositoryDecorator $repository;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->repository = $this->app->make(CachedActionRepositoryDecorator::class);
    }

    public function testAllIsCached(): void
    {
        PassportScopeAction::factory()->count(2)->create();

        $first = $this->repository->all();

        PassportScopeAction::factory()->create();

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
        PassportScopeAction::factory()->create(['is_active' => true]);
        PassportScopeAction::factory()->create(['is_active' => false]);

        $first = $this->repository->active();

        PassportScopeAction::factory()->create(['is_active' => true]);

        $second = $this->repository->active();

        self::assertCount(1, $first);
        self::assertCount(1, $second);
    }

    public function testFindByNameIsCached(): void
    {
        PassportScopeAction::factory()->create([
            'name' => 'read',
        ]);

        $first = $this->repository->findByName('read');

        PassportScopeAction::where('name', 'read')->delete();

        $second = $this->repository->findByName('read');

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
        PassportScopeAction::factory()->count(2)->create();

        self::assertCount(2, $this->repository->all());

        Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.actions',
        ])->flush();

        PassportScopeAction::factory()->create();

        self::assertCount(3, $this->repository->all());
    }
}
