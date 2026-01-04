<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Owners;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class GetAllOwnersRelationshipUseCaseTest extends DatabaseTestCase
{
    private GetAllOwnersRelationshipUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllOwnersRelationshipUseCase::class);
    }

    public function testExecuteReturnsOwnersWithLabels(): void
    {
        $first = User::factory()->create(['name' => 'First Owner']);
        $second = User::factory()->create(['name' => 'Second Owner']);

        config([
            'passport-ui.owner_model' => $first::class,
            'passport-ui.owner_label_attribute' => 'name',
        ]);

        $options = $this->useCase->execute();

        self::assertSame(
            [
                $first->getKey() => 'First Owner',
                $second->getKey() => 'Second Owner',
            ],
            $options->all()
        );
    }
}
