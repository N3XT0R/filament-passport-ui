<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Resources;

use N3XT0R\FilamentPassportUi\Application\UseCases\Resources\DeleteResourceUseCase;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class DeleteResourceUseCaseTest extends DatabaseTestCase
{
    private DeleteResourceUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(DeleteResourceUseCase::class);
    }

    public function testExecuteDeletesResource(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'temporary-resource',
        ]);

        $result = $this->useCase->execute($resource);

        self::assertTrue($result);
        self::assertDatabaseMissing($resource->getTable(), [
            'id' => $resource->getKey(),
            'name' => 'temporary-resource',
        ]);
    }
}
