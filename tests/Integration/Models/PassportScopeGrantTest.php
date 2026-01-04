<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Models;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class PassportScopeGrantTest extends DatabaseTestCase
{
    public function testRelationsAndScopeString(): void
    {
        $tokenable = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $resource = PassportScopeResource::create([
            'name' => 'profile',
            'description' => 'Profile resource',
        ]);

        $action = PassportScopeAction::create([
            'name' => 'update',
            'description' => 'Update profile',
            'resource_id' => $resource->id,
        ]);

        $grant = PassportScopeGrant::create([
            'tokenable_type' => $tokenable::class,
            'tokenable_id' => (string) $tokenable->getKey(),
            'resource_id' => $resource->id,
            'action_id' => $action->id,
        ]);

        $grant->refresh();

        $this->assertTrue($grant->tokenable->is($tokenable));
        $this->assertTrue($grant->resource->is($resource));
        $this->assertTrue($grant->action->is($action));
        $this->assertSame('profile.update', $grant->toScopeString());
    }
}
