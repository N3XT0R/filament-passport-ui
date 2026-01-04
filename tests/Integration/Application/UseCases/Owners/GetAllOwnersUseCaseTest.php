<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Owners;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\GetAllOwnersUseCase;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class GetAllOwnersUseCaseTest extends DatabaseTestCase
{
    private GetAllOwnersUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllOwnersUseCase::class);
    }

    public function testExecuteReturnsAllOwners(): void
    {
        [$first, $second] = User::factory()->count(2)->create();

        $owners = $this->useCase->execute();

        self::assertCount(2, $owners);
        self::assertTrue($owners->contains(fn(User $user) => $user->getKey() === $first->getKey()));
        self::assertTrue($owners->contains(fn(User $user) => $user->getKey() === $second->getKey()));
    }
}
