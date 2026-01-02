<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Enum;

/**
 * OAuth2 Grant Types
 */
enum OAuthClientType: string
{
    case AUTHORIZATION_CODE = 'authorization_code';
    case CLIENT_CREDENTIALS = 'client_credentials';
    case PASSWORD = 'password';
    case PERSONAL_ACCESS = 'personal_access';
    case IMPLICIT = 'implicit';

    case DEVICE = 'device';

    /**
     * Get all enum values
     * @return array
     */
    public static function values(): array
    {
        return array_map(
            static fn(self $case) => $case->value,
            self::cases()
        );
    }

    public static function all(): array
    {
        return self::cases();
    }
}
