<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ClientResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeReturnsClientCount(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        ClientFactory::new()->count(2)->create();

        Livewire::test(ListClients::class);

        $this->assertSame('2', ClientResource::getNavigationBadge());
    }

    public function testGetEloquentQueryScopesToCurrentUserWhenSelfServiceEnabled(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        $ownClient = ClientFactory::new()->create([
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass(),
        ]);
        ClientFactory::new()->create([
            'owner_id' => $otherOwner->getKey(),
            'owner_type' => $otherOwner->getMorphClass(),
        ]);

        $panel = \Filament\Panel::make()->id('client-self-service-test');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        \Filament\Facades\Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $clients = ClientResource::getEloquentQuery()->get();

        $this->assertCount(1, $clients);
        $this->assertTrue($clients->first()->is($ownClient));
    }

    public function testGetEloquentQueryReturnsAllClientsWhenSelfServiceDisabled(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $owner = User::factory()->create();
        $otherOwner = User::factory()->create();

        ClientFactory::new()->create(['owner_id' => $owner->getKey(), 'owner_type' => $owner->getMorphClass()]);
        ClientFactory::new()->create(['owner_id' => $otherOwner->getKey(), 'owner_type' => $otherOwner->getMorphClass()]);

        $panel = \Filament\Panel::make()->id('client-admin-mode-test');
        $panel->plugin(FilamentPassportUiPlugin::make());
        \Filament\Facades\Filament::setCurrentPanel($panel);

        $this->actingAs($owner, 'web');

        $this->assertCount(2, ClientResource::getEloquentQuery()->get());
    }
}
