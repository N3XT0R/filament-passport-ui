<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactory;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactoryInterface;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\OAuthClientCreationStrategyInterface;

/**
 * Boot the OAuth client factory with the appropriate strategies based on configuration.
 */
class OAuthClientFactoryBooter extends BaseBooter
{

    public function boot(): void
    {
        $this->app->singleton(OAuthClientFactoryInterface::class, function ($app) {
            $allowedTypeValues = config(
                'passport-ui.oauth.allowed_grant_types',
                []
            );

            $allowedTypes = array_map(
                static fn(string $value): OAuthClientType => OAuthClientType::from($value),
                $allowedTypeValues
            );

            $strategies = collect($app->tagged('filament-passport-ui.oauth.strategies'))
                ->filter(function (OAuthClientCreationStrategyInterface $strategy) use ($allowedTypes) {
                    return array_any($allowedTypes, fn(OAuthClientType $type): bool => $strategy->supports($type));
                })
                ->values();

            if ($strategies->isEmpty()) {
                throw new \RuntimeException(
                    'No OAuth client strategies enabled. Check filament-passport-ui.oauth.allowed_grant_types.'
                );
            }

            return new OAuthClientFactory(
                strategies: $strategies
            );
        });
    }
}
