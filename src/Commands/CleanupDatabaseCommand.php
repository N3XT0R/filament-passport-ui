<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Commands;

use N3XT0R\LaravelPassportAuthorizationCore\Commands\CleanupDatabaseCommand as BaseCommand;

class CleanupDatabaseCommand extends BaseCommand
{
    protected $signature = 'filament-passport-ui:cleanup-database';

    protected $description = 'Cleans up obsolete data from the Filament Passport UI database.';
}
