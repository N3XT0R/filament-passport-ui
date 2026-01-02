<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Enum;

/**
 * OAuth2 Grant Types
 */
enum GrantTypeEnum
{
    case PERSONAL_ACCESS;
    case PASSWORD;
    case CLIENT_CREDENTIALS;
    case IMPLICIT;
    case AUTHORIZATION_CODE;
    case DEVICE;
}
