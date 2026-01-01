<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class DatabaseTestCase extends TestCase
{
    use LazilyRefreshDatabase;


    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
