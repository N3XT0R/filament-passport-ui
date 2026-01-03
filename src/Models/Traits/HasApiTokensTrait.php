<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Traits;

use Laravel\Passport\HasApiTokens as BaseHasApiTokens;
use N3XT0R\FilamentPassportUi\Services\GrantService;

trait HasApiTokensTrait
{
    use BaseHasApiTokens {
        BaseHasApiTokens::tokenCan as parentTokenCan;
    }

    /**
     * Determine if the token has a given scope with additional scope grants check.
     * @param string $scope
     * @return bool
     */
    public function tokenCan(string $scope): bool
    {
        $result = $this->parentTokenCan($scope);
        if (true === $result && $this->oauthApps()->count() > 0) {
            $result = app(GrantService::class)->tokenableHasGrantToScope(
                $this,
                $scope
            );
        }


        return $result;
    }
}
