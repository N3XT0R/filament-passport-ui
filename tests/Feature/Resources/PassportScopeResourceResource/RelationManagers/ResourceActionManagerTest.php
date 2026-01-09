<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Resources\PassportScopeResourceResource\RelationManagers;

use App\Models\User;
use Livewire\Livewire;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages\EditResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers\ResourceActionManager;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Database\Factories\PassportScopeActionFactory;
use N3XT0R\LaravelPassportAuthorizationCore\Database\Factories\PassportScopeResourceFactory;

class ResourceActionManagerTest extends DatabaseTestCase
{
    public function testRelationManagerDisplaysResourceAndGlobalActions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create();
        $resourceActions = PassportScopeActionFactory::new()->count(2)->withResource($resource)->create();
        $globalAction = PassportScopeActionFactory::new()->create(['resource_id' => null]);
        PassportScopeActionFactory::new()->withResource()->create();

        Livewire::test(ResourceActionManager::class, [
            'ownerRecord' => $resource,
            'pageClass' => EditResource::class,
        ])
            ->assertCountTableRecords(3)
            ->assertCanSeeTableRecords($resourceActions->push($globalAction));
    }

    public function testCreateActionDefaultsToOwnerResource(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create();

        Livewire::test(ResourceActionManager::class, [
            'ownerRecord' => $resource,
            'pageClass' => EditResource::class,
        ])
            ->callTableAction('create', data: [
                'name' => 'approve',
                'description' => 'Approve transactions',
                'is_active' => true,
            ])
            ->assertHasNoTableActionErrors();

        /** @var PassportScopeAction $createdAction */
        $createdAction = PassportScopeAction::query()->where('name', 'approve')->first();

        $this->assertNotNull($createdAction);
        $this->assertSame($resource->getKey(), $createdAction->resource_id);
    }

    public function testGlobalActionsHideEditAndDeleteActions(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = PassportScopeResourceFactory::new()->create();
        $globalAction = PassportScopeActionFactory::new()->create(['resource_id' => null]);

        Livewire::test(ResourceActionManager::class, [
            'ownerRecord' => $resource,
            'pageClass' => EditResource::class,
        ])
            ->assertTableActionHidden('edit', record: $globalAction)
            ->assertTableActionHidden('delete', record: $globalAction);
    }
}
