<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Illuminate\Support\Collection;
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

    /**
     * Get all scope grants for the given tokenable.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @return Collection
     */
    public function getTokenableGrants(HasPassportScopeGrantsInterface $tokenable): Collection
    {
        return $tokenable->passportScopeGrants()
            ->with(['resource', 'action'])
            ->get();
    }
}
