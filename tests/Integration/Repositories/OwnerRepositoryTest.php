<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Repositories\OwnerRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class OwnerRepositoryTest extends DatabaseTestCase
{
    protected OwnerRepository $ownerRepository;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'passport-ui.owner_model' => User::class,
        ]);

        $this->ownerRepository = $this->app->make(OwnerRepository::class);
    }

    public function testFindByKeyReturnsOwner(): void
    {
        $user = User::factory()->create();

        $found = $this->ownerRepository->findByKey($user->getKey());

        self::assertNotNull($found);
        self::assertSame($user->getKey(), $found->getKey());
    }

    public function testFindByKeyReturnsNullWhenNotFound(): void
    {
        $found = $this->ownerRepository->findByKey(999999);

        self::assertNull($found);
    }

    public function testAllReturnsAllOwners(): void
    {
        User::factory()->count(3)->create();

        $owners = $this->ownerRepository->all();

        self::assertCount(3, $owners);
        self::assertContainsOnlyInstancesOf(User::class, $owners);
    }
}
