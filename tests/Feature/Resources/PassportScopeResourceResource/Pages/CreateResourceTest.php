<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeResourceResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\CreateResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class CreateResourceTest extends DatabaseTestCase
{
    public function testScopeResourceCanBeCreated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(CreateResource::class)
            ->fillForm([
                'name' => 'projects',
                'description' => 'Manage project entities.',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $resource = PassportScopeResource::query()->first();

        $this->assertNotNull($resource);
        $this->assertSame('projects', $resource->name);
        $this->assertSame('Manage project entities.', $resource->description);
        $this->assertTrue($resource->is_active);
    }
}
