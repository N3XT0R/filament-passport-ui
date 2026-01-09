<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeActionResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages\CreateAction;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;

class CreateActionTest extends DatabaseTestCase
{
    public function testScopeActionCanBeCreated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create();

        Livewire::test(CreateAction::class)
            ->fillForm([
                'name' => 'approve-transfers',
                'description' => 'Approve pending transfers.',
                'resource_id' => $resource->getKey(),
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $action = PassportScopeAction::query()->first();

        $this->assertNotNull($action);
        $this->assertSame('approve-transfers', $action->name);
        $this->assertSame('Approve pending transfers.', $action->description);
        $this->assertTrue($action->is_active);
        $this->assertSame($resource->getKey(), $action->resource_id);
    }
}
