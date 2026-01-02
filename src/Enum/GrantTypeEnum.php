<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Enum;

/**
 * OAuth2 Grant Types
 */
enum GrantTypeEnum: string
{
    case AUTHORIZATION_CODE = 'authorization_code';
    case CLIENT_CREDENTIALS = 'client_credentials';
    case DEVICE_CODE = 'device_code';
    case IMPLICIT = 'implicit';
    case PASSWORD = 'password';
    case PERSONAL_ACCESS = 'personal_access';
}
