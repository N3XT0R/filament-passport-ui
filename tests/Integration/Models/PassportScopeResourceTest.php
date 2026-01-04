<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Models;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class PassportScopeResourceTest extends DatabaseTestCase
{
    public function testActionsRelationReturnsAssociatedScopeActions(): void
    {
        $resource = PassportScopeResource::create([
            'name' => 'videos',
            'description' => 'Videos resource',
            'is_active' => false,
        ]);

        $action = PassportScopeAction::create([
            'name' => 'read',
            'description' => 'Read videos',
            'resource_id' => $resource->id,
            'is_active' => true,
        ]);

        $resource->refresh();

        $this->assertFalse($resource->is_active);
        $this->assertCount(1, $resource->actions);
        $this->assertTrue($resource->actions->first()->is($action));
    }
}
