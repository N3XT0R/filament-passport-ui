<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeResourceResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\EditResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class EditResourceTest extends DatabaseTestCase
{
    public function testScopeResourceCanBeUpdated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create([
            'name' => 'projects',
            'description' => 'Manage project entities.',
            'is_active' => true,
        ]);

        Livewire::test(EditResource::class, ['record' => $resource->getKey()])
            ->fillForm([
                'name' => 'users',
                'description' => 'Manage user accounts.',
                'is_active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $resource->refresh();

        $this->assertSame('users', $resource->name);
        $this->assertSame('Manage user accounts.', $resource->description);
        $this->assertFalse($resource->is_active);
    }
}
