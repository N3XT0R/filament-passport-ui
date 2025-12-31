<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Contracts;

interface IsMigratedContract
{
    /**
     * Check if the necessary migrations have been run.
     * @return bool
     */
    public function isMigrated(): bool;
}
