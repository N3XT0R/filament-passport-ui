<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Owners;

use App\Models\User;
use InvalidArgumentException;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\SaveOwnershipRelationUseCase;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class SaveOwnershipRelationUseCaseTest extends DatabaseTestCase
{
    private SaveOwnershipRelationUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(SaveOwnershipRelationUseCase::class);
    }

    public function testExecuteAssociatesClientWithOwner(): void
    {
        $client = Client::factory()->create();
        $owner = User::factory()->create();

        $this->useCase->execute($client->getKey(), $owner->getKey());

        $client->refresh();

        self::assertSame($owner->getKey(), $client->owner?->getKey());
    }

    public function testExecuteThrowsWhenOwnerIsMissing(): void
    {
        $client = Client::factory()->create();

        $this->expectException(InvalidArgumentException::class);

        $this->useCase->execute($client->getKey(), 123456);
    }
}
