<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Contracts;

interface IsMigratedContract
{
    public function isMigrated(): bool;
}
