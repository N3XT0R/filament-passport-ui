<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasPassportScopeGrantsInterface extends HasRelationshipsInterface
{
    public function passportScopeGrants(): MorphMany;

    public function tokenCan(string $scope): bool;
}
