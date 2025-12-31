<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
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

                if (($params['cache'] ?? false) || true === (bool)config('passport-ui.cache.enabled', false)) {
                    $repository = new CachedActionRepositoryDecorator(
                        innerRepository: $repository,
                    );
                }

                return $repository;
            }
        );
    }
}
