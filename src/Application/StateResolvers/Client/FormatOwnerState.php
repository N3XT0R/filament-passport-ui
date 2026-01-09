<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\Client;

use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class FormatOwnerState
{
    public function execute(?string $state, ?Client $record): string|int|null
    {
        if ($record === null) {
            return $state;
        }

        return $record->owner?->getKey();
    }
}
