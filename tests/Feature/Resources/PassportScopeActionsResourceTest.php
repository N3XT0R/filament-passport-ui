<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages\ListActions;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class PassportScopeActionsResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeReturnsActionCount(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        PassportScopeActionFactory::new()->count(3)->create();

        Livewire::test(ListActions::class);

        $this->assertSame('3', PassportScopeActionsResource::getNavigationBadge());
    }
}
