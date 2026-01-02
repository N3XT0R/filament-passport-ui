<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;

readonly class GrantTypesRepository
{
    /**
     *
     * @return OAuthClientType[]
     */
    public function active(): array
    {
        $allowed = config('filament-passport-ui.oauth.allowed_grant_types', []);
        foreach ($allowed as $key => $value) {
            $allowed[$key] = OAuthClientType::from($value);
        }
        return $allowed;
    }
}
