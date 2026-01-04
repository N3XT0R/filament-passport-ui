<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Providers\Register;

use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\AuthorizationCodeClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ClientCredentialsClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\DeviceGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ImplicitGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PasswordGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PersonalAccessClientStrategy;
use N3XT0R\FilamentPassportUi\Providers\Register\OAuthStrategyRegistrar;
use N3XT0R\FilamentPassportUi\Tests\TestCase;

final class OAuthStrategyRegistrarTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [];
    }

    public function testItRegistersOAuthStrategiesAsTaggedServices(): void
    {
        $this->app->make(OAuthStrategyRegistrar::class)->register();

        $services = iterator_to_array(
            $this->app->tagged('filament-passport-ui.oauth.strategies')
        );

        self::assertContains(
            PersonalAccessClientStrategy::class,
            array_map(static fn($service) => $service::class, $services)
        );

        self::assertContains(
            PasswordGrantClientStrategy::class,
            array_map(static fn($service) => $service::class, $services)
        );

        self::assertContains(
            ClientCredentialsClientStrategy::class,
            array_map(static fn($service) => $service::class, $services)
        );

        self::assertContains(
            ImplicitGrantClientStrategy::class,
            array_map(static fn($service) => $service::class, $services)
        );

        self::assertContains(
            AuthorizationCodeClientStrategy::class,
            array_map(static fn($service) => $service::class, $services)
        );

        self::assertContains(
            DeviceGrantClientStrategy::class,
            array_map(static fn($service) => $service::class, $services)
        );
    }
}
