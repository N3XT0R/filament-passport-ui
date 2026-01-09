<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
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
        $config = (array)config('passport-ui.models');

        foreach ($config as $modelType => $modelClass) {
            if (empty($modelClass) && $modelType !== 'client') {
                continue;
            }

            $this->registerModel($modelType, $modelClass);
        }
    }

    /**
     * Register a custom Passport model based on the model type.
     * @param string $modelType
     * @param string|null $modelClass
     * @return void
     */
    private function registerModel(string $modelType, ?string $modelClass = null): void
    {
        match ($modelType) {
            'client' => Passport::useClientModel(empty($modelClass) ? Client::class : $modelClass),
            'token' => Passport::useTokenModel($modelClass),
            'auth_code' => Passport::useAuthCodeModel($modelClass),
            'refresh_token' => Passport::useRefreshTokenModel($modelClass),
            default => null,
        };
    }

}
