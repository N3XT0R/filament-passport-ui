<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Providers\Boot;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactory;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactoryInterface;
use N3XT0R\FilamentPassportUi\Providers\Boot\OAuthClientFactoryBooter;
use N3XT0R\FilamentPassportUi\Providers\Boot\OAuthStrategyBooter;
use N3XT0R\FilamentPassportUi\Tests\TestCase;
use RuntimeException;

final class OAuthClientFactoryBooterTest extends TestCase
{
    public function testItRegistersOAuthClientFactoryWhenStrategiesAreAllowed(): void
    {
        config([
            'passport-ui.oauth.allowed_grant_types' => [
                OAuthClientType::PERSONAL_ACCESS->value,
            ],
        ]);

        $this->app->make(OAuthStrategyBooter::class)->boot();
        $this->app->make(OAuthClientFactoryBooter::class)->boot();

        $factory = $this->app->make(OAuthClientFactoryInterface::class);

        self::assertInstanceOf(OAuthClientFactory::class, $factory);
    }

    public function testItThrowsExceptionWhenNoStrategiesAreEnabled(): void
    {
        config([
            'passport-ui.oauth.allowed_grant_types' => [],
        ]);

        $this->app->make(OAuthStrategyBooter::class)->boot();
        $this->app->make(OAuthClientFactoryBooter::class)->boot();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'No OAuth client strategies enabled. Check filament-passport-ui.oauth.allowed_grant_types.'
        );

        $this->app->make(OAuthClientFactoryInterface::class);
    }
}
