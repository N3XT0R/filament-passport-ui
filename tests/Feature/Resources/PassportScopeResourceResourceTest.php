<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\ListResources;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class PassportScopeResourceResourceTest extends DatabaseTestCase
{
    public function testNavigationBadgeReturnsResourceCount(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        PassportScopeResourceFactory::new()->count(4)->create();

        Livewire::test(ListResources::class);

        $this->assertSame('4', PassportScopeResourceResource::getNavigationBadge());
    }
}
