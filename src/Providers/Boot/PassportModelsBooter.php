<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Providers\Boot\Concerns\BooterInterface;

/**
 * Class PassportModelsBooter
 *
 * This class is responsible for booting custom Passport models
 * as defined in the configuration.
 */
class PassportModelsBooter implements BooterInterface
{
    public function boot(): void
    {
        $config = config('passport-ui.models');

        foreach ($config as $modelType => $modelClass) {
            if (empty($modelClass)) {
                continue;
            }
            match ($modelType) {
                'client' => Passport::useClientModel($modelClass),
                'token' => Passport::useTokenModel($modelClass),
                'auth_code' => Passport::useAuthCodeModel($modelClass),
                'refresh_token' => Passport::useRefreshTokenModel($modelClass),
                default => null,
            };
        }
    }

}
