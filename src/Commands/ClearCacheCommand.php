<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Commands;

use Illuminate\Console\Command;
use N3XT0R\FilamentPassportUi\Application\UseCases\Cleanup\ClearCacheUseCase;

class ClearCacheCommand extends Command
{
    protected $signature = 'filament-passport-ui:cleanup-cache';

    protected $description = 'Clears the Filament Passport UI cache, including scope registry cache.';

    public function handle(ClearCacheUseCase $clearCacheUseCase): int
    {
        try {
            $clearCacheUseCase->execute();
        } catch (\Throwable $e) {
            $this->error('An error occurred while clearing cache: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}