<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Actions;

use N3XT0R\FilamentPassportUi\Application\UseCases\Actions\DeleteActionUseCase;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class DeleteActionUseCaseTest extends DatabaseTestCase
{
    private DeleteActionUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(DeleteActionUseCase::class);
    }

    public function testExecuteDeletesAction(): void
    {
        $resource = PassportScopeResource::factory()->create();
        $action = PassportScopeAction::factory()->withResource($resource)->create([
            'name' => 'delete',
        ]);

        $result = $this->useCase->execute($action);

        self::assertTrue($result);
        self::assertDatabaseMissing($action->getTable(), [
            'id' => $action->getKey(),
            'name' => 'delete',
        ]);
    }
}
