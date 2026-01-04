<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Providers\Boot\ScopeBooter;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ScopeBooterTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Passport::tokensCan([]);
    }

    public function testItDoesNothingWhenDatabaseScopesAreDisabled(): void
    {
        config(['passport-ui.use_database_scopes' => false]);

        $this->seedScopes();

        $this->app->make(ScopeBooter::class)->boot();

        self::assertTrue(Passport::scopes()->isEmpty());
    }

    public function testItDoesNothingWhenScopesAreNotMigrated(): void
    {
        config(['passport-ui.use_database_scopes' => true]);

        $this->app->make(ScopeBooter::class)->boot();

        self::assertTrue(Passport::scopes()->isEmpty());
    }

    public function testItRegistersScopesFromDatabase(): void
    {
        config(['passport-ui.use_database_scopes' => true]);

        $this->seedScopes();

        $this->app->make(ScopeBooter::class)->boot();

        self::assertSame(
            [
                'users:read' => 'User management: Read users',
                'users:update' => 'User management: Update users',
            ],
            Passport::scopes()
                ->pluck('description', 'id')
                ->toArray()
        );
    }

    private function seedScopes(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'users',
            'description' => 'User management',
        ]);

        PassportScopeAction::factory()->create([
            'resource_id' => $resource->getKey(),
            'name' => 'read',
            'description' => 'Read users',
        ]);

        PassportScopeAction::factory()->create([
            'resource_id' => $resource->getKey(),
            'name' => 'update',
            'description' => 'Update users',
        ]);
    }
}
