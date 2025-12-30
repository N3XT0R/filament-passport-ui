<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\ClientRepository as BaseClientRepository;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

class PassportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BaseClientRepository::class, ClientRepository::class);
    }

    public function boot(): void
    {
        $this->bootScopes();
    }

    protected function bootScopes(): void
    {
        $scopeRegistryService = app(ScopeRegistryService::class);
        if (false === $scopeRegistryService->isMigrated()) {
            return;
        }

        Passport::tokensCan($scopeRegistryService->all()->toArray());
    }
}
