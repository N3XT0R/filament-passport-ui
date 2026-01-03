<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Observers\ClientObserver;
use N3XT0R\FilamentPassportUi\Providers\Boot\Concerns\BooterInterface;

class ObserverBooter implements BooterInterface
{
    public function boot(): void
    {
        Passport::clientModel()::observe(ClientObserver::class);
    }
}
