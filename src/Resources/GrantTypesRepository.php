<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;

readonly class GrantTypesRepository
{
    /**
     * Get active OAuth client types based on configuration
     * @return OAuthClientType[]
     */
    public function active(): array
    {
        $allowed = config('passport-ui.oauth.allowed_grant_types', []);
        foreach ($allowed as $value) {
            $allowed[] = OAuthClientType::from($value);
        }

        return $allowed;
    }
}
