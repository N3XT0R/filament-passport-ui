<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Register;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as Application;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;

/**
 * Register repositories on the container.
 */
class RepositoryRegistrar extends BaseRegistrar
{
    public function register(): void
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
