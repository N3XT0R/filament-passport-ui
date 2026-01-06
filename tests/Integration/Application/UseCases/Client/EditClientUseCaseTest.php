<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Client;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use N3XT0R\FilamentPassportUi\Application\UseCases\Client\EditClientUseCase;
use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientRevokedEvent;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class EditClientUseCaseTest extends DatabaseTestCase
{
    private EditClientUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(EditClientUseCase::class);
    }

    public function testExecuteUpdatesClientAndOwner(): void
    {
        $client = Client::factory()->create([
            'name' => 'Before Edit',
            'redirect_uris' => ['https://before.example'],
            'revoked' => false,
        ]);

        $newOwner = User::factory()->create();

        $updated = $this->useCase->execute($client, [
            'name' => 'After Edit',
            'redirect_uris' => ['https://after.example'],
            'owner' => $newOwner,
            'scopes' => [],
            'revoked' => true,
        ]);

        self::assertSame('After Edit', $updated->name);
        self::assertSame(['https://after.example'], $updated->redirect_uris);
        self::assertTrue($updated->revoked);
        self::assertSame($newOwner->getKey(), $updated->owner?->getKey());
        $this->assertDatabaseHas($client->getTable(), [
            'id' => $client->getKey(),
            'name' => 'After Edit',
            'revoked' => true,
        ]);
        Event::assertDispatched(OAuthClientRevokedEvent::class);
    }
}
