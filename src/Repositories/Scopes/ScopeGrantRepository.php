<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes;

use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;

class ScopeGrantRepository
{

    /**
     * Create a new scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @return PassportScopeGrant
     */
    public function createScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
    ): PassportScopeGrant {
        return PassportScopeGrant::create([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resourceId,
            'action_id' => $actionId,
        ]);
    }

    /**
     * Create or update a scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @return PassportScopeGrant
     */
    public function createOrUpdateScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
    ): PassportScopeGrant {
        return PassportScopeGrant::updateOrCreate([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resourceId,
            'action_id' => $actionId,
        ]);
    }

    /**
     * Delete a scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @return bool
     */
    public function deleteScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
    ): bool {
        return PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey())
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId)
            ->delete();
    }
}
