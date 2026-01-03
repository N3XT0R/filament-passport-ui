<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Observers\ClientObserver;
use N3XT0R\FilamentPassportUi\Observers\PassportScopeActionObserver;
use N3XT0R\FilamentPassportUi\Observers\PassportScopeResourceObserver;

class ObserverBooter extends BaseBooter
{
    public function boot(): void
    {
        Passport::clientModel()::observe(ClientObserver::class);
        PassportScopeResource::observe(PassportScopeResourceObserver::class);
        PassportScopeAction::observe(PassportScopeActionObserver::class);
    }
}
