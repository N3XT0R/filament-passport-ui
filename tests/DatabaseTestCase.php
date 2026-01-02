<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Workbench\App\Models\User;

#[WithMigration]
class DatabaseTestCase extends TestCase
{
    use LazilyRefreshDatabase;


    protected function defineEnvironment($app): void
    {
        $config = $app->make(Repository::class);

        $config->set([
            'auth.defaults.provider' => 'users',
            'auth.providers.users.model' => User::class,
            'auth.guards.api' => ['driver' => 'passport', 'provider' => 'users'],
            'database.default' => 'testing',
            'database.connections.testing' => [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => false,
            ],
        ]);
    }
}
