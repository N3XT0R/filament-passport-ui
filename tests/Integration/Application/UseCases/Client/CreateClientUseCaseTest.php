<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Client;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use N3XT0R\FilamentPassportUi\Application\UseCases\Client\CreateClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientCreatedEvent;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class CreateClientUseCaseTest extends DatabaseTestCase
{
    private CreateClientUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(CreateClientUseCase::class);
    }

    public function testExecuteCreatesClientWithOwner(): void
    {
        $owner = User::factory()->create();

        $result = $this->useCase->execute([
            'name' => 'Integration Client',
            'redirect_uris' => ['https://example.test/callback'],
            'grant_type' => OAuthClientType::PERSONAL_ACCESS->value,
            'owner' => $owner,
            'scopes' => [],
        ]);

        self::assertInstanceOf(Client::class, $result->client);
        self::assertNotEmpty($result->plainSecret);
        self::assertSame('Integration Client', $result->client->name);
        self::assertSame($owner->getKey(), $result->client->owner?->getKey());

        $this->assertDatabaseHas($result->client->getTable(), [
            'id' => $result->client->getKey(),
            'name' => 'Integration Client',
        ]);

        Event::assertDispatched(OAuthClientCreatedEvent::class);
    }
}
