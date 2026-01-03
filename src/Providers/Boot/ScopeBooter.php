<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Providers\Boot\Concerns\BooterInterface;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

/**
 * Class ScopeBooter
 *
 * This class is responsible for booting the scopes from the database
 * into Laravel Passport's token capabilities.
 */
readonly class ScopeBooter implements BooterInterface
{

    public function __construct(
        private ScopeRegistryService $scopeRegistry
    ) {
    }


    public function boot(): void
    {
        if (!config('passport-ui.use_database_scopes', false)) {
            return;
        }

        if (!$this->scopeRegistry->isMigrated()) {
            return;
        }

        Passport::tokensCan(
            $this->scopeRegistry->all()->toArray()
        );
    }
}
