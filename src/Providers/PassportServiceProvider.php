<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactory;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactoryInterface;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use Illuminate\Contracts\Container\Container as Application;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\{OAuthClientCreationStrategyInterface,
    PersonalAccessClientStrategy,
    PasswordGrantClientStrategy,
    ClientCredentialsClientStrategy,
    ImplicitGrantClientStrategy,
    AuthorizationCodeClientStrategy,
    DeviceGrantClientStrategy
};

class PassportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BaseClientRepository::class, ClientRepository::class);
        $this->registerOAuthFactory();
        $this->registerOAuthStrategies();
        $this->registerRepositories();
    }

    public function boot(): void
    {
        $this->bootScopes();
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
                static fn (string $value): OAuthClientType => OAuthClientType::from($value),
                $allowedTypeValues
            );

            $strategies = collect($app->tagged('filament-passport-ui.oauth.strategies'))
                ->filter(function (OAuthClientCreationStrategyInterface $strategy) use ($allowedTypes) {
                    return array_any($allowedTypes, fn (OAuthClientType $type): bool => $strategy->supports($type));
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
        $this->app->bind(ConfigRepository::class);
        $this->app->singleton(
            ActionRepositoryContract::class,
            fn (Application $app, array $params = []) => $this->makeRepository(
                app: $app,
                params: $params,
                repositoryClass: ActionRepository::class,
                decoratorClass: CachedActionRepositoryDecorator::class,
            )
        );

        $this->app->singleton(
            ResourceRepositoryContract::class,
            fn (Application $app, array $params = []) => $this->makeRepository(
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
