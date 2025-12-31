<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Passport\Traits;

use Laravel\Passport\Http\Middleware\CheckToken;
use Laravel\Passport\Http\Middleware\CheckTokenForAnyScope;

trait HasPassportScopesTrait
{
    /**
     * Generate the middleware string to require the given scopes.
     *
     * @param string ...$scopes
     * @return string
     */
    public static function requires(string ...$scopes): string
    {
        return CheckToken::using(...$scopes);
    }

    /**
     * Generate the middleware string to require any of the given scopes.
     *
     * @param string ...$scopes
     * @return string
     */
    public static function requireAny(string ...$scopes): string
    {
        return CheckTokenForAnyScope::using(...$scopes);
    }
}
