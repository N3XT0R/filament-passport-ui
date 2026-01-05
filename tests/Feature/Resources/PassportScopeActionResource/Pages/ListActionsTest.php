<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeActionResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages\ListActions;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ListActionsTest extends DatabaseTestCase
{
    public function testListActionsPageRegistersCreateHeaderAction(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $livewire = Livewire::test(ListActions::class);

        $headerActions = $livewire->instance()->getHeaderActions();

        $this->assertNotEmpty($headerActions);
        $this->assertSame('create', $headerActions[0]->getName());
    }
}
