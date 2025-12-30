<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

class PassportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootScopes();
    }

    protected function bootScopes(): void
    {
        $scopeRegistryService = app(ScopeRegistryService::class);
        Passport::tokensCan($scopeRegistryService->all()->toArray());
    }
}
