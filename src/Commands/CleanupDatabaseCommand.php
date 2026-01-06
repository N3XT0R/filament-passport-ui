<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Commands;

use Illuminate\Console\Command;
use N3XT0R\FilamentPassportUi\Application\UseCases\Cleanup\CleanUpUseCase;

class CleanupDatabaseCommand extends Command
{
    protected $signature = 'filament-passport-ui:cleanup-database';

    protected $description = 'Cleans up obsolete data from the Filament Passport UI database.';

    public function handle(CleanUpUseCase $cleanUpUseCase): int
    {
        try {
            $cleanUpUseCase->execute();
        } catch (\Throwable $e) {
            $this->error('An error occurred while cleanup: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
