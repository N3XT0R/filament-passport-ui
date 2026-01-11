<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\GrantType;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;

class NeedsUserPermissionState
{
    public function execute(?string $grantType = null): bool
    {
        if (empty($grantType)) {
            return false;
        }


        return OAuthClientType::from($grantType)
            !== OAuthClientType::CLIENT_CREDENTIALS;
    }
}