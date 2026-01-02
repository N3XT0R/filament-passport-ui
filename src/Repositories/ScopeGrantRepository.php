<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;

class ScopeGrantRepository
{

    /**
     * Check if the given tokenable has a specific scope grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @return bool
     */
    public function tokenableHasGrant(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
    ): bool {
        return $tokenable->passportScopeGrants()
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId)
            ->exists();
    }
}
