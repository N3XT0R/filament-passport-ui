<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Models;

use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

class PassportScopeActionTest extends DatabaseTestCase
{
    public function testResourceRelationProvidesOwningResource(): void
    {
        $resource = PassportScopeResource::create([
            'name' => 'orders',
            'description' => 'Orders resource',
        ]);

        $action = PassportScopeAction::create([
            'name' => 'write',
            'description' => 'Write orders',
            'resource_id' => $resource->id,
            'is_active' => false,
        ]);

        $action->refresh();

        $this->assertFalse($action->is_active);
        $this->assertTrue($action->resource->is($resource));
    }
}
