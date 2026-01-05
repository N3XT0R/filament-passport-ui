<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\ClientFactory;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ClientResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeReturnsClientCount(): void
    {
        config()->set('passport-ui.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        ClientFactory::new()->count(2)->create();

        Livewire::test(ListClients::class);

        $this->assertSame('2', ClientResource::getNavigationBadge());
    }
}
