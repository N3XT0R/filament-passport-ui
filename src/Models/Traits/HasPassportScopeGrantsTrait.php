<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use LogicException;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;

trait HasPassportScopeGrantsTrait
{
    public function passportScopeGrants(): MorphMany
    {
        if ($this instanceof HasPassportScopeGrantsInterface === false) {
            throw new LogicException(
                sprintf(
                    '%s must implement %s to use HasPassportScopeGrantsTrait.',
                    Model::class,
                    HasPassportScopeGrantsInterface::class
                )
            );
        }

        return $this->morphMany(
            PassportScopeGrant::class,
            'tokenable'
        );
    }
}
