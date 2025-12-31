<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers;

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

    protected function registerRepositories(): void
    {
        $this->app->bind(
            ActionRepositoryContract::class,
            function (Application $app, array $params = []) {
                $repository = $app->make(ActionRepository::class);

                $useCache = $params['cache']
                    ?? config('passport-ui.cache.enabled', false);

                if ($useCache) {
                    $repository = new CachedActionRepositoryDecorator($repository);
                }

                return $repository;
            }
        );

        $this->app->bind(
            ResourceRepositoryContract::class,
            function (Application $app, array $params = []) {
                $repository = $app->make(ResourceRepository::class);

                $useCache = $params['cache']
                    ?? config('passport-ui.cache.enabled', false);

                if ($useCache) {
                    $repository = new CachedResourceRepositoryDecorator($repository);
                }

                return $repository;
            }
        );
    }
}
