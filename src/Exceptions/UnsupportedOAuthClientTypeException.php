<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Exceptions;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use RuntimeException;

final class UnsupportedOAuthClientTypeException extends RuntimeException
{
    public static function forType(OAuthClientType $type): self
    {
        return new self(
            sprintf('No OAuth client creation strategy found for type "%s".', $type->value)
        );
    }
}
