<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\Client;

use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class FormatClientGrantTypeState
{
    public function execute(?string $state, ?Client $record): ?string
    {
        if ($record === null) {
            return $state;
        }

        $grantTypes = (array)$record->getAttribute('grant_types');

        return current($grantTypes);
    }
}
