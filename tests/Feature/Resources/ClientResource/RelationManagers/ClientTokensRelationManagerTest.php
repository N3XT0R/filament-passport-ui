<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\RelationManagers;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\TokenFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ViewClient;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\RelationManagers\ClientTokensRelationManager;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class ClientTokensRelationManagerTest extends DatabaseTestCase
{
    public function testRelationManagerDisplaysOnlyClientTokens(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        /** @var Client $client */
        $client = ClientFactory::new()->create();
        $clientTokens = TokenFactory::new()->count(2)->withClient($client)->create();

        TokenFactory::new()->withClient()->create();

        Livewire::test(ClientTokensRelationManager::class, [
            'ownerRecord' => $client,
            'pageClass' => ViewClient::class,
        ])
            ->assertCountTableRecords(2)
            ->assertCanSeeTableRecords($clientTokens);
    }

    public function testTableUsesTokenResourceConfiguration(): void
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

        $this->assertSame('tokens', ClientTokensRelationManager::getRelationshipName());
        $this->assertSame('updated_at', $table->getDefaultSortColumn());
        $this->assertSame('desc', $table->getDefaultSortDirection());

        $component
            ->assertTableColumnExists('id')
            ->assertTableColumnExists('user_id')
            ->assertTableColumnExists('client_id')
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('scopes')
            ->assertTableColumnExists('revoked')
            ->assertTableColumnExists('created_at')
            ->assertTableColumnExists('expires_at');
    }
}
