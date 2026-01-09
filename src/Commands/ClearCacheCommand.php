<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Commands;

use N3XT0R\LaravelPassportAuthorizationCore\Commands\ClearCacheCommand as BaseCommand;

class ClearCacheCommand extends BaseCommand
{
    protected $signature = 'filament-passport-ui:cleanup-cache';

    protected $description = 'Clears the Filament Passport UI cache, including scope registry cache.';
}