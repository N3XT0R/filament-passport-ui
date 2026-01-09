<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Database\Factories\PassportScopeGrantFactory;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class CleanupDatabaseCommandTest extends DatabaseTestCase
{

    public function testCommandRemovesOrphanedGrantsAndSucceeds(): void
    {
        $resource = PassportScopeResource::factory()->create();
        $action = PassportScopeAction::factory()->withResource($resource)->create();

        /** @var PassportScopeGrantFactory $grantFactory */
        $grantFactory = PassportScopeGrant::factory();
        $grantFactory->create([
            'tokenable_type' => User::class,
            'tokenable_id' => 999999,
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);

        $owner = User::factory()->create();
        $grantFactory->withTokenable($owner)->create([
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);

        $this->artisan('filament-passport-ui:cleanup-database')
            ->assertExitCode(Command::SUCCESS);

        $this->assertDatabaseMissing('passport_scope_grants', [
            'tokenable_id' => 999999,
            'tokenable_type' => User::class,
        ]);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_id' => $owner->getKey(),
            'tokenable_type' => $owner->getMorphClass(),
        ]);
    }

}
