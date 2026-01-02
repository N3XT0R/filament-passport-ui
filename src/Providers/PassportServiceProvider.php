<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use Illuminate\Contracts\Container\Container as Application;

class PassportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BaseClientRepository::class, ClientRepository::class);
        $this->registerFactories();
        $this->registerRepositories();
    }

    public function boot(): void
    {
        $this->bootScopes();
    }

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

    protected function registerFactories(): void
    {
    }

    protected function registerRepositories(): void
    {
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

        if (!$useCache) {
            return $repository;
        }

        return new $decoratorClass($repository);
    }
}
