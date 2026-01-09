<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;

final class ActionRepositoryTest extends DatabaseTestCase
{
    protected ActionRepository $actionRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionRepository = $this->app->make(ActionRepository::class);
    }

    public function testAllReturnsAllActions(): void
    {
        PassportScopeAction::factory()->count(3)->create();

        $actions = $this->actionRepository->all();

        self::assertInstanceOf(Collection::class, $actions);
        self::assertCount(3, $actions);
        self::assertContainsOnlyInstancesOf(PassportScopeAction::class, $actions);
    }

    public function testActiveReturnsOnlyActiveActions(): void
    {
        PassportScopeAction::factory()->create(['is_active' => true]);
        PassportScopeAction::factory()->create(['is_active' => true]);
        PassportScopeAction::factory()->create(['is_active' => false]);

        $active = $this->actionRepository->active();

        self::assertCount(2, $active);
        self::assertTrue(
            $active->every(
                static fn(PassportScopeAction $action) => $action->is_active === true
            )
        );
    }

    public function testFindByNameReturnsAction(): void
    {
        $action = PassportScopeAction::factory()->create([
            'name' => 'read',
        ]);

        $found = $this->actionRepository->findByName('read');

        self::assertNotNull($found);
        self::assertSame($action->getKey(), $found->getKey());
    }

    public function testFindByNameReturnsNullWhenNotFound(): void
    {
        self::assertNull(
            $this->actionRepository->findByName('non-existing')
        );
    }

    public function testIsMigratedReturnsTrueWhenTableExists(): void
    {
        self::assertTrue(
            $this->actionRepository->isMigrated()
        );
    }

    public function testCountReturnsTotalNumberOfActions(): void
    {
        PassportScopeAction::factory()->count(5)->create();

        self::assertSame(
            5,
            $this->actionRepository->count()
        );
    }
}
