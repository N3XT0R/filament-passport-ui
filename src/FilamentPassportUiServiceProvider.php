<?php

namespace N3XT0R\FilamentPassportUi;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use Laravel\Passport\Passport;
use Livewire\Features\SupportTesting\Testable;
use N3XT0R\FilamentPassportUi\Commands\FilamentPassportUiCommand;
use N3XT0R\FilamentPassportUi\Database\Seeders\FilamentPassportUiDatabaseSeeder;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactory;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactoryInterface;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\AuthorizationCodeClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ClientCredentialsClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\DeviceGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ImplicitGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\OAuthClientCreationStrategyInterface;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PasswordGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PersonalAccessClientStrategy;
use N3XT0R\FilamentPassportUi\Observers\ClientObserver;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;
use N3XT0R\FilamentPassportUi\Testing\TestsFilamentPassportUi;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            })
            ->hasConfigFile('passport-ui')
            ->hasMigrations($this->getMigrations())
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void
    {
        $this->registerRepositories();
        $this->registerOAuthStrategies();
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

        $this->registerOAuthFactory();
        $this->bootScopes();
        $this->bootObserver();
    }

    protected function bootObserver(): void
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
            'create_passport_scope_grants_table',
        ];
    }

    /**
     * Boot the OAuth scopes from the database if enabled.
     * @return void
     */
    protected function bootScopes(): void
    {
        if (false === (bool)config('passport-ui.use_database_scopes', false)) {
            return;
        }

        $scopeRegistryService = app(ScopeRegistryService::class);
        if (false === $scopeRegistryService->isMigrated()) {
            return;
        }

        Passport::tokensCan($scopeRegistryService->all()->toArray());
    }

    /**
     * Register OAuth client creation strategies.
     * @return void
     */
    protected function registerOAuthStrategies(): void
    {
        $this->app->tag([
            PersonalAccessClientStrategy::class,
            PasswordGrantClientStrategy::class,
            ClientCredentialsClientStrategy::class,
            ImplicitGrantClientStrategy::class,
            AuthorizationCodeClientStrategy::class,
            DeviceGrantClientStrategy::class,
        ], 'filament-passport-ui.oauth.strategies');
    }

    /**
     * Register the OAuth client factory.
     * @return void
     */
    protected function registerOAuthFactory(): void
    {
        $this->app->singleton(OAuthClientFactoryInterface::class, function ($app) {
            $allowedTypeValues = config(
                'filament-passport-ui.oauth.allowed_grant_types',
                []
            );

            $allowedTypes = array_map(
                static fn(string $value): OAuthClientType => OAuthClientType::from($value),
                $allowedTypeValues
            );

            $strategies = collect($app->tagged('filament-passport-ui.oauth.strategies'))
                ->filter(function (OAuthClientCreationStrategyInterface $strategy) use ($allowedTypes) {
                    return array_any($allowedTypes, fn(OAuthClientType $type): bool => $strategy->supports($type));
                })
                ->values();

            if ($strategies->isEmpty()) {
                throw new \RuntimeException(
                    'No OAuth client strategies enabled. Check filament-passport-ui.oauth.allowed_grant_types.'
                );
            }

            return new OAuthClientFactory(
                strategies: $strategies
            );
        });
    }

    protected function registerRepositories(): void
    {
        $this->app->singleton(BaseClientRepository::class, ClientRepository::class);
        $this->app->singleton(ConfigRepository::class);
        $this->app->singleton(
            ActionRepositoryContract::class,
            fn(Application $app, array $params = []) => $this->makeRepository(
                app: $app,
                params: $params,
                repositoryClass: ActionRepository::class,
                decoratorClass: CachedActionRepositoryDecorator::class,
            )
        );

        $this->app->singleton(
            ResourceRepositoryContract::class,
            fn(Application $app, array $params = []) => $this->makeRepository(
                app: $app,
                params: $params,
                repositoryClass: ResourceRepository::class,
                decoratorClass: CachedResourceRepositoryDecorator::class,
            )
        );
    }

    /**
     * Make a repository instance, optionally decorated with caching.
     * @template TRepository
     * @template TDecorator
     *
     * @param class-string<TRepository> $repositoryClass
     * @param class-string<TDecorator> $decoratorClass
     * @throws BindingResolutionException
     */
    protected function makeRepository(
        Application $app,
        array $params,
        string $repositoryClass,
        string $decoratorClass,
    ): object {
        $repository = $app->make($repositoryClass);

        $useCache = $params['cache'] ?? (bool)config('passport-ui.cache.enabled', false);

        if (
            !$useCache
            || defined('TESTBENCH_CORE')
            || $this->app->runningUnitTests()
            || $this->app->environment('testing')
        ) {
            return $repository;
        }

        return new $decoratorClass($repository);
    }
}
