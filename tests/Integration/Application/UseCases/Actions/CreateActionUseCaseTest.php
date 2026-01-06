<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Actions;

use Illuminate\Support\Facades\Event;
use N3XT0R\FilamentPassportUi\Application\UseCases\Actions\CreateActionUseCase;
use N3XT0R\FilamentPassportUi\Events\PassportScopeAction\ActionCreatedEvent;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class CreateActionUseCaseTest extends DatabaseTestCase
{
    private CreateActionUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();

        $this->useCase = $this->app->make(CreateActionUseCase::class);
    }

    public function testExecuteCreatesActionWithResource(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'posts',
        ]);

        $action = $this->useCase->execute([
            'name' => 'create',
            'description' => 'Create posts',
            'resource_id' => $resource->getKey(),
            'is_active' => true,
        ]);

        self::assertInstanceOf(PassportScopeAction::class, $action);
        self::assertSame('create', $action->name);
        self::assertSame($resource->getKey(), $action->resource_id);
        self::assertTrue($action->is_active);

        $this->assertDatabaseHas($action->getTable(), [
            'id' => $action->getKey(),
            'name' => 'create',
            'resource_id' => $resource->getKey(),
            'is_active' => true,
        ]);


        Event::assertDispatched(ActionCreatedEvent::class);
    }
}
