<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Passport;

use Laravel\Passport\Client as PassportClient;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\Traits\HasPassportScopeGrantsTrait;
use N3XT0R\FilamentPassportUi\Services\GrantService;

class Client extends PassportClient implements HasPassportScopeGrantsInterface
{
    use HasPassportScopeGrantsTrait;

    public function hasScope(string $scope): bool
    {
        return app(GrantService::class)->tokenableHasGrantToScope(
            $this,
            $scope
        );
    }
}
