<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Actions\DeleteAction;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class ClientResourceActionTest extends DatabaseTestCase
{
    public function testDeleteActionRequiresConfirmationAndDeletesClient(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $action = DeleteAction::make()->record($client);

        self::assertTrue($action->isConfirmationRequired());
        self::assertTrue($action->call());
        self::assertNull(Client::find($client->getKey()));
    }
}
