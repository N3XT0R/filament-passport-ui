<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Observers\PassportScopeResourceObserver;

class ObserverBooter extends BaseBooter
{
    public function boot(): void
    {
        PassportScopeResource::observe(PassportScopeResourceObserver::class);
    }
}
