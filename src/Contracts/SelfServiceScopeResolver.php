<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;

/**
 * Decides which scopes a self-service user may put on their own OAuth client.
 *
 * The package cannot answer this on its own: which scopes a user is entitled to
 * is an application concern (roles, permissions, plan, tenant). Applications
 * bind an implementation through the `passport-ui.self_service_scope_resolver`
 * config key.
 *
 * When no resolver is configured, self-service users may choose freely from the
 * configured scope taxonomy, which is the behaviour up to and including 2.3.0.
 */
interface SelfServiceScopeResolver
{
    /**
     * Scopes the actor may select, as "resource:action" strings.
     *
     * Returning null means "no restriction". Returning an empty collection
     * means "no scope at all", which hides the scope selection entirely, so
     * only return that when the actor is genuinely entitled to nothing.
     *
     * @return Collection<int, string>|null
     */
    public function allowedScopesFor(?Authenticatable $actor): ?Collection;
}
