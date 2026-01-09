<?php

namespace N3XT0R\FilamentPassportUi\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use N3XT0R\FilamentPassportUi\FilamentPassportUiServiceProvider;
use N3XT0R\LaravelPassportAuthorizationCore\LaravelPassportAuthorizationCoreServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Activitylog\ActivitylogServiceProvider;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            static fn(string $modelName) => 'N3XT0R\\FilamentPassportUi\\Database\\Factories\\' . class_basename(
                    $modelName
                ) . 'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            ActivitylogServiceProvider::class,
            LaravelPassportAuthorizationCoreServiceProvider::class,
            FilamentPassportUiServiceProvider::class
        ];
    }
}
