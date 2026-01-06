<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Resources;

use Illuminate\Support\Facades\Event;
use N3XT0R\FilamentPassportUi\Application\UseCases\Resources\EditResourceUseCase;
use N3XT0R\FilamentPassportUi\Events\PassportScopeResource\ResourceUpdatedEvent;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class EditResourceUseCaseTest extends DatabaseTestCase
{
    private EditResourceUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(EditResourceUseCase::class);
    }

    public function testExecuteUpdatesResource(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'articles',
            'description' => 'Old description',
            'is_active' => true,
        ]);

        $updated = $this->useCase->execute($resource, [
            'name' => 'posts',
            'description' => 'Updated description',
            'is_active' => false,
        ]);

        self::assertInstanceOf(PassportScopeResource::class, $updated);
        self::assertSame('posts', $updated->name);
        self::assertSame('Updated description', $updated->description);
        self::assertFalse($updated->is_active);

        $this->assertDatabaseHas($updated->getTable(), [
            'id' => $updated->getKey(),
            'name' => 'posts',
            'is_active' => false,
        ]);

        Event::assertDispatched(ResourceUpdatedEvent::class);
    }
}
