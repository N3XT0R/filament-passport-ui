<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources\ClientResource;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ViewClient;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\RelationManagers\ClientTokensRelationManager;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class ClientResourceRelationManagersTest extends DatabaseTestCase
{
    public function testRelationManagerUsesTokenResourceTableConfiguration(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create();

        $component = Livewire::test(ClientTokensRelationManager::class, [
            'ownerRecord' => $client,
            'pageClass' => ViewClient::class,
        ]);

        $table = $component->instance()->getTable();

        self::assertSame('tokens', ClientTokensRelationManager::getRelationshipName());
        self::assertSame('updated_at', $table->getDefaultSortColumn());
        self::assertSame('desc', $table->getDefaultSortDirection());
    }
}
