<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Enum;

/**
 * OAuth2 Grant Types
 */
enum OAuthClientType: string
{
    case PERSONAL_ACCESS = 'personal_access';
    case PASSWORD = 'password';
    case CLIENT_CREDENTIALS = 'client_credentials';
    case IMPLICIT = 'implicit';
    case AUTHORIZATION_CODE = 'authorization_code';
    case DEVICE = 'device';
}
