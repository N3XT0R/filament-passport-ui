<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Models\Passport;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\Client as PassportClient;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\Traits\HasPassportScopeGrantsTrait;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Services\GrantService;

class Client extends PassportClient implements HasPassportScopeGrantsInterface
{
    use HasPassportScopeGrantsTrait;
    use HasFactory;

    public function hasScope(string $scope): bool
    {
        $configRepository = app(ConfigRepository::class);
        if ($configRepository->isUsingDatabaseScopes() === false) {
            return parent::hasScope($scope);
        }

        return app(GrantService::class)->tokenableHasGrantToScope(
            $this,
            $scope
        );
    }
}
