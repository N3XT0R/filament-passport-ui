<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Cleanup;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Application\UseCases\Cleanup\CleanUpUseCase;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeGrantFactory;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class CleanUpUseCaseTest extends DatabaseTestCase
{
    private CleanUpUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(CleanUpUseCase::class);
    }

    public function testExecuteDeletesOrphanedGrants(): void
    {
        $resource = PassportScopeResource::factory()->create();
        $action = PassportScopeAction::factory()->withResource($resource)->create();

        /** @var PassportScopeGrantFactory $factory */
        $factory = PassportScopeGrant::factory();
        $factory->create([
            'tokenable_type' => User::class,
            'tokenable_id' => 999999,
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);

        $owner = User::factory()->create();
        $factory->withTokenable($owner)->create([
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);

        $this->useCase->execute();

        self::assertDatabaseMissing('passport_scope_grants', [
            'tokenable_id' => 999999,
            'tokenable_type' => User::class,
        ]);

        self::assertDatabaseHas('passport_scope_grants', [
            'tokenable_id' => $owner->getKey(),
            'tokenable_type' => $owner->getMorphClass(),
        ]);
    }
}
