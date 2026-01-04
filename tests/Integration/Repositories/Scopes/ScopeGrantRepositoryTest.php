<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes;

use Illuminate\Support\Collection;
use Laravel\Passport\Client as PassportClient;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ScopeGrantRepositoryTest extends DatabaseTestCase
{
    protected ScopeGrantRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ScopeGrantRepository::class);
    }

    public function testCreateScopeGrantForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        $grant = $this->repository->createScopeGrantForTokenable(
            $client,
            1,
            1
        );

        self::assertSame($client->getMorphClass(), $grant->tokenable_type);
        self::assertSame($client->getKey(), $grant->tokenable_id);
    }

    public function testCreateOrUpdateScopeGrantForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        $first = $this->repository->createOrUpdateScopeGrantForTokenable($client, 1, 1);
        $second = $this->repository->createOrUpdateScopeGrantForTokenable($client, 1, 1);

        self::assertSame($first->getKey(), $second->getKey());
        self::assertSame(1, PassportScopeGrant::count());
    }

    public function testDeleteScopeGrantForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        $deleted = $this->repository->deleteScopeGrantForTokenable(
            $client,
            $grant->resource_id,
            $grant->action_id
        );

        self::assertSame(1, $deleted);
        self::assertSame(0, PassportScopeGrant::count());
    }

    public function testTokenableHasScopeGrant(): void
    {
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        self::assertTrue(
            $this->repository->tokenableHasScopeGrant($client, 1, 1)
        );
    }

    public function testGetTokenableGrants(): void
    {
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->count(2)
            ->withTokenable($client)
            ->create();

        $grants = $this->repository->getTokenableGrants($client);

        self::assertInstanceOf(Collection::class, $grants);
        self::assertCount(2, $grants);
    }

    public function testDeleteAllGrantsForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->count(3)
            ->withTokenable($client)
            ->create();

        $deleted = $this->repository->deleteAllGrantsForTokenable($client);

        self::assertSame(3, $deleted);
        self::assertSame(0, PassportScopeGrant::count());
    }

    public function testDeleteTokenableOrphans(): void
    {
        PassportClient::flushEventListeners();
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        $client->delete();

        $this->repository->deleteTokenableOrphans();
        self::assertSame(0, PassportScopeGrant::count());
    }
}
