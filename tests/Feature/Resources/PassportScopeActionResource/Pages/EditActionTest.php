<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeActionResource\Pages;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeActionFactory;
use N3XT0R\FilamentPassportUi\Database\Factories\PassportScopeResourceFactory;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages\EditAction;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class EditActionTest extends DatabaseTestCase
{
    public function testScopeActionCanBeUpdated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $originalResource = PassportScopeResourceFactory::new()->create();

        $action = PassportScopeActionFactory::new()
            ->withResource($originalResource)
            ->create([
                'name' => 'review-transfers',
                'description' => 'Review pending transfers.',
                'is_active' => true,
            ]);

        $newResource = PassportScopeResourceFactory::new()->create();

        Livewire::test(EditAction::class, ['record' => $action->getKey()])
            ->fillForm([
                'name' => 'approve-transfers',
                'description' => 'Approve transfers in queue.',
                'resource_id' => $newResource->getKey(),
                'is_active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $action->refresh();

        $this->assertSame('approve-transfers', $action->name);
        $this->assertSame('Approve transfers in queue.', $action->description);
        $this->assertFalse($action->is_active);
        $this->assertSame($newResource->getKey(), $action->resource_id);
    }
}
