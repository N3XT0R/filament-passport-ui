<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\ClientResource\Pages;

use App\Models\User;
use Filament\Actions\CreateAction;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages\ListClients;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ListClientsTest extends DatabaseTestCase
{
    public function testListClientsPageShowsCreateAction(): void
    {
        config()->set('passport-authorization-core.use_database_scopes', false);

        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(ListClients::class);

        $headerActions = $component->instance()->getHeaderActions();

        $this->assertCount(1, $headerActions);
        $this->assertInstanceOf(CreateAction::class, $headerActions[0]);
    }
}
