<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeResourceResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\CreateResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

class CreateResourceTest extends DatabaseTestCase
{
    public function testScopeResourceCanBeCreated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(CreateResource::class)
            ->fillForm([
                'name' => 'transactions',
                'description' => 'Manages transaction scopes.',
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $resource = PassportScopeResource::query()->first();

        $this->assertNotNull($resource);
        $this->assertSame('transactions', $resource->name);
        $this->assertSame('Manages transaction scopes.', $resource->description);
        $this->assertTrue($resource->is_active);
    }
}
