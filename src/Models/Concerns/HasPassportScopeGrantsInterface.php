<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasPassportScopeGrantsInterface extends HasRelationshipsInterface
{
    /**
     * Get all the passport scope grants for the tokenable model.
     * @return MorphMany
     */
    public function passportScopeGrants(): MorphMany;

    /**
     * Determine if the token has a given scope with additional scope grants check.
     * @param string $scope
     * @return bool
     */
    public function tokenCan(string $scope): bool;
}
