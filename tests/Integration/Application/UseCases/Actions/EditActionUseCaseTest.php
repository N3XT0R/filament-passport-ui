<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Actions;

use Illuminate\Support\Facades\Event;
use N3XT0R\FilamentPassportUi\Application\UseCases\Actions\EditActionUseCase;
use N3XT0R\FilamentPassportUi\Events\PassportScopeAction\ActionUpdatedEvent;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class EditActionUseCaseTest extends DatabaseTestCase
{
    private EditActionUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(EditActionUseCase::class);
    }

    public function testExecuteUpdatesAction(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'posts',
        ]);
        $action = PassportScopeAction::factory()->withResource($resource)->create([
            'name' => 'view',
            'description' => 'View posts',
            'is_active' => true,
        ]);

        $newResource = PassportScopeResource::factory()->create([
            'name' => 'comments',
        ]);

        $updated = $this->useCase->execute($action, [
            'name' => 'update',
            'description' => 'Update posts',
            'resource_id' => $newResource->getKey(),
            'is_active' => false,
        ]);

        self::assertInstanceOf(PassportScopeAction::class, $updated);
        self::assertSame('update', $updated->name);
        self::assertSame('Update posts', $updated->description);
        self::assertSame($newResource->getKey(), $updated->resource_id);
        self::assertFalse($updated->is_active);

        $this->assertDatabaseHas($updated->getTable(), [
            'id' => $updated->getKey(),
            'name' => 'update',
            'resource_id' => $newResource->getKey(),
            'is_active' => false,
        ]);

        Event::assertDispatched(ActionUpdatedEvent::class);
    }
}
