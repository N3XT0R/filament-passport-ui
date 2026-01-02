<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Passport\HasApiTokens;
use LogicException;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Services\GrantService;

trait HasPassportScopeGrantsTrait
{
    use HasApiTokens {
        tokenCan as parentTokenCan;
    }

    public function passportScopeGrants(): MorphMany
    {
        $this->ensureImplementsHasPassportScopeGrantsInterface();
        return $this->morphMany(
            PassportScopeGrant::class,
            'tokenable'
        );
    }

    /**
     * Determine if the token has a given scope.
     * @param string $scope
     * @return bool
     */
    public function tokenCan(string $scope): bool
    {
        $result = $this->parentTokenCan($scope);
        $this->ensureImplementsHasPassportScopeGrantsInterface();

        if (true === $result) {
            $result = app(GrantService::class)->tokenableHasGrantToScope(
                $this,
                $scope
            );
        }


        return $result;
    }


    private function ensureImplementsHasPassportScopeGrantsInterface(): void
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
    }
}
