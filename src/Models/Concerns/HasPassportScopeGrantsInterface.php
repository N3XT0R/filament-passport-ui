<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasPassportScopeGrantsInterface
{
    public function passportScopeGrants(): MorphMany;

    public function morphMany($related, $name, $type = null, $id = null, $localKey = null);
}
