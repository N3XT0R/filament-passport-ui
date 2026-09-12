<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Fixtures;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Contracts\SelfServiceScopeResolver;

/**
 * Test double standing in for the host application's scope policy.
 *
 * Returns whatever `passport-ui.test_allowed_scopes` holds, so a test can set
 * the allow-list without going through application-specific concepts.
 */
class ConfigurableScopeResolver implements SelfServiceScopeResolver
{
    public function allowedScopesFor(?Authenticatable $actor): ?Collection
    {
        $allowed = config('passport-ui.test_allowed_scopes');

        return $allowed === null ? null : collect($allowed);
    }
}
