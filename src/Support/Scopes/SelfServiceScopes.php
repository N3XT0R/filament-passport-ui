<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Scopes;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Contracts\SelfServiceScopeResolver;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;

/**
 * Resolves the self-service scope allow-list through the application.
 *
 * Outside self-service mode there is no restriction at all: an administrator
 * defines a client's declared capability freely.
 */
final class SelfServiceScopes
{
    /**
     * @return Collection<int, string>|null null means "no restriction"
     */
    public static function allowedFor(?Authenticatable $actor): ?Collection
    {
        if (!FilamentPassportUiPlugin::get()->isSelfService()) {
            return null;
        }

        return static::resolver()?->allowedScopesFor($actor);
    }

    private static function resolver(): ?SelfServiceScopeResolver
    {
        $resolver = config('passport-ui.self_service_scope_resolver');

        if (blank($resolver)) {
            return null;
        }

        $instance = is_string($resolver) ? app($resolver) : $resolver;

        return $instance instanceof SelfServiceScopeResolver ? $instance : null;
    }
}
