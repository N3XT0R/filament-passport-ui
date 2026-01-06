<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Client;

use N3XT0R\FilamentPassportUi\Application\UseCases\Client\DeleteClientUseCase;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class DeleteClientUseCaseTest extends DatabaseTestCase
{
    private DeleteClientUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(DeleteClientUseCase::class);
    }

    public function testExecuteDeletesClient(): void
    {
        $client = Client::factory()->create([
            'name' => 'Client to delete',
        ]);

        $result = $this->useCase->execute($client);

        self::assertTrue($result);
        self::assertDatabaseMissing($client->getTable(), [
            'id' => $client->getKey(),
            'name' => 'Client to delete',
        ]);
    }
}
