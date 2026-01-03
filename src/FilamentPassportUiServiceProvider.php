<?php

namespace N3XT0R\FilamentPassportUi;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Livewire\Features\SupportTesting\Testable;
use N3XT0R\FilamentPassportUi\Commands\FilamentPassportUiCommand;
use N3XT0R\FilamentPassportUi\Database\Seeders\FilamentPassportUiDatabaseSeeder;
use N3XT0R\FilamentPassportUi\Testing\TestsFilamentPassportUi;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

;

class FilamentPassportUiServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-passport-ui';

    public static string $viewNamespace = 'filament-passport-ui';

    /**
     * @var array|\class-string[]
     */
    protected array $registrars = [
        Providers\Register\RepositoryRegistrar::class,
        Providers\Register\OAuthStrategyRegistrar::class,
    ];
    /**
     * @var array|\class-string[]
     */
    protected array $booter = [
        Providers\Boot\ScopeBooter::class,
        Providers\Boot\PassportModelsBooter::class,
        Providers\Boot\ObserverBooter::class,
        Providers\Boot\OAuthClientFactoryBooter::class,
    ];

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->endWith(function (InstallCommand $command) {
                        if (!$command->confirm(
                            question: 'Do you want to seed default Passport scope resources and actions?',
                            default: false
                        )) {
                            return;
                        }

                        $command->comment('Seeding default Passport scope resources and actions...');
                        Artisan::call('db:seed', [
                            '--class' => FilamentPassportUiDatabaseSeeder::class,
                        ]);
                    })
                    ->askToStarRepoOnGitHub('n3xt0r/filament-passport-ui');
            })
            ->hasConfigFile('passport-ui')
            ->hasMigrations($this->getMigrations())
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void
    {
        $this->executeRegistrars();
    }

    private function executeRegistrars(): void
    {
        foreach ($this->registrars as $registrar) {
            $registrarInstance = app($registrar);
            if ($registrarInstance instanceof Providers\Register\Concerns\RegistrarInterface) {
                $registrarInstance->register();
            }
        }
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if ($this->app->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-passport-ui/{$file->getFilename()}"),
                ], 'filament-passport-ui-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentPassportUi());

        $this->executeBooter();
    }

    private function executeBooter(): void
    {
        foreach ($this->booter as $booterClass) {
            $booter = app($booterClass);
            if ($booter instanceof Providers\Boot\Concerns\BooterInterface) {
                $booter->boot();
            }
        }
    }

    protected function getAssetPackageName(): ?string
    {
        return 'n3xt0r/filament-passport-ui';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-passport-ui', __DIR__ . '/../resources/dist/components/filament-passport-ui.js'),
            // Css::make('filament-passport-ui-styles', __DIR__ . '/../resources/dist/filament-passport-ui.css'),
            // Js::make('filament-passport-ui-scripts', __DIR__ . '/../resources/dist/filament-passport-ui.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentPassportUiCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_passport_scope_resources_table',
            'create_passport_scope_actions_table',
            'create_passport_scope_grant_table',
        ];
    }
}
