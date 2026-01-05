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
            'name' => 'transactions',
            'description' => 'Manage transaction scopes.',
            'is_active' => true,
        ]);

        Livewire::test(EditResource::class, ['record' => $resource->getKey()])
            ->fillForm([
                'name' => 'payments',
                'description' => 'Manage payment scopes.',
                'is_active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $resource->refresh();

        $this->assertSame('payments', $resource->name);
        $this->assertSame('Manage payment scopes.', $resource->description);
        $this->assertFalse($resource->is_active);
    }
}
