<?php

namespace N3XT0R\FilamentPassportUi;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;
use Livewire\Features\SupportTesting\Testable;
use N3XT0R\FilamentPassportUi\Commands\FilamentPassportUiCommand;
use N3XT0R\FilamentPassportUi\Observers\ClientObserver;
use N3XT0R\FilamentPassportUi\Testing\TestsFilamentPassportUi;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use N3XT0R\FilamentPassportUi\Database\Seeders\FilamentPassportUiDatabaseSeeder;

class FilamentPassportUiServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-passport-ui';

    public static string $viewNamespace = 'filament-passport-ui';

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
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
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
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-passport-ui/{$file->getFilename()}"),
                ], 'filament-passport-ui-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentPassportUi());
    }

    public function bootingPackage(): void
    {
        Client::observe(ClientObserver::class);
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
        ];
    }
}
