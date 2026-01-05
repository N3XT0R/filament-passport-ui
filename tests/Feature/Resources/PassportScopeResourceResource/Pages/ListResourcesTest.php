<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeResourceResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\ListResources;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class ListResourcesTest extends DatabaseTestCase
{
    public function testListResourcesPageShowsExistingResources(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create([
            'name' => 'projects',
            'description' => 'Manage project entities.',
        ]);

        Livewire::test(ListResources::class)
            ->assertSee($resource->name)
            ->assertSee($resource->description);
    }
}
