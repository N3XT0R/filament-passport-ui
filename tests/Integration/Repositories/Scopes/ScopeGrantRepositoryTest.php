<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes;

use Illuminate\Database\Eloquent\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ScopeGrantRepositoryTest extends DatabaseTestCase
{
    protected ScopeGrantRepository $scopeGrantRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scopeGrantRepository = $this->app->make(ScopeGrantRepository::class);
    }

    public function testCreateScopeGrantForTokenable(): void
    {
        [$tokenable, $resource, $action] = $this->seedTokenableWithScope();

        $grant = $this->scopeGrantRepository
            ->createScopeGrantForTokenable(
                $tokenable,
                $resource->getKey(),
                $action->getKey()
            );

        self::assertInstanceOf(PassportScopeGrant::class, $grant);
        self::assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);
    }

    public function testCreateOrUpdateScopeGrantForTokenable(): void
    {
        [$tokenable, $resource, $action] = $this->seedTokenableWithScope();

        $first = $this->scopeGrantRepository
            ->createOrUpdateScopeGrantForTokenable(
                $tokenable,
                $resource->getKey(),
                $action->getKey()
            );

        $second = $this->scopeGrantRepository
            ->createOrUpdateScopeGrantForTokenable(
                $tokenable,
                $resource->getKey(),
                $action->getKey()
            );

        self::assertSame($first->getKey(), $second->getKey());
        self::assertSame(1, PassportScopeGrant::count());
    }

    public function testDeleteScopeGrantForTokenable(): void
    {
        [$tokenable, $resource, $action] = $this->seedTokenableWithScope(true);

        $deleted = $this->scopeGrantRepository
            ->deleteScopeGrantForTokenable(
                $tokenable,
                $resource->getKey(),
                $action->getKey()
            );

        self::assertSame(1, $deleted);
        self::assertDatabaseCount('passport_scope_grants', 0);
    }

    public function testTokenableHasScopeGrant(): void
    {
        [$tokenable, $resource, $action] = $this->seedTokenableWithScope(true);

        self::assertTrue(
            $this->scopeGrantRepository->tokenableHasScopeGrant(
                $tokenable,
                $resource->getKey(),
                $action->getKey()
            )
        );
    }

    public function testTokenableHasGrantViaRelation(): void
    {
        [$tokenable, $resource, $action] = $this->seedTokenableWithScope(true);

        self::assertTrue(
            $this->scopeGrantRepository->tokenableHasGrant(
                $tokenable,
                $resource->getKey(),
                $action->getKey()
            )
        );
    }

    public function testGetTokenableGrants(): void
    {
        [$tokenable] = $this->seedTokenableWithScope(true);

        $grants = $this->scopeGrantRepository->getTokenableGrants($tokenable);

        self::assertInstanceOf(Collection::class, $grants);
        self::assertCount(1, $grants);
        self::assertTrue($grants->first()->relationLoaded('resource'));
        self::assertTrue($grants->first()->relationLoaded('action'));
    }

    public function testDeleteAllGrantsForTokenable(): void
    {
        [$tokenable] = $this->seedTokenableWithScope(true);
        $this->seedAdditionalGrant($tokenable);

        $deleted = $this->scopeGrantRepository
            ->deleteAllGrantsForTokenable($tokenable);

        self::assertSame(2, $deleted);
        self::assertDatabaseCount('passport_scope_grants', 0);
    }

    public function testDeleteTokenableOrphans(): void
    {
        $grant = PassportScopeGrant::factory()->create();

        $deleted = $this->scopeGrantRepository->deleteTokenableOrphans();

        self::assertSame(1, $deleted);
        self::assertDatabaseCount('passport_scope_grants', 0);
    }

    private function seedTokenableWithScope(bool $withGrant = false): array
    {
        $tokenable = $this->createTokenable();

        $resource = PassportScopeResource::factory()->create();
        $action = PassportScopeAction::factory()->create();

        if ($withGrant) {
            PassportScopeGrant::factory()->create([
                'tokenable_type' => $tokenable->getMorphClass(),
                'tokenable_id' => $tokenable->getKey(),
                'resource_id' => $resource->getKey(),
                'action_id' => $action->getKey(),
            ]);
        }

        return [$tokenable, $resource, $action];
    }

    private function seedAdditionalGrant($tokenable): void
    {
        PassportScopeGrant::factory()->create([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
        ]);
    }

    private function createTokenable()
    {
        return app(config('passport-ui.owner_model'))->create();
    }
}
