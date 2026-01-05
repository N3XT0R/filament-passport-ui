<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeResourceResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\ListResources;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ListResourcesTest extends DatabaseTestCase
{
    public function testListResourcesPageRegistersCreateHeaderAction(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $livewire = Livewire::test(ListResources::class);

        $headerActions = $livewire->instance()->getHeaderActions();

        $this->assertNotEmpty($headerActions);
        $this->assertSame('create', $headerActions[0]->getName());
    }
}
