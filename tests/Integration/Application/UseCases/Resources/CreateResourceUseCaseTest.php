<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Resources;

use Illuminate\Support\Facades\Event;
use N3XT0R\FilamentPassportUi\Application\UseCases\Resources\CreateResourceUseCase;
use N3XT0R\FilamentPassportUi\Events\PassportScopeResource\ResourceCreatedEvent;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class CreateResourceUseCaseTest extends DatabaseTestCase
{
    private CreateResourceUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(CreateResourceUseCase::class);
    }

    public function testExecuteCreatesResource(): void
    {
        $resource = $this->useCase->execute([
            'name' => 'articles',
            'description' => 'Manage articles',
            'is_active' => true,
        ]);

        self::assertInstanceOf(PassportScopeResource::class, $resource);
        self::assertSame('articles', $resource->name);
        self::assertSame('Manage articles', $resource->description);
        self::assertTrue($resource->is_active);

        $this->assertDatabaseHas($resource->getTable(), [
            'id' => $resource->getKey(),
            'name' => 'articles',
            'is_active' => true,
        ]);

        Event::assertDispatched(ResourceCreatedEvent::class);
    }
}
