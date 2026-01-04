<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Providers\Boot;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Providers\Boot\PassportModelsBooter;
use N3XT0R\FilamentPassportUi\Tests\TestCase;

final class PassportModelsBooterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Passport::useClientModel(\Laravel\Passport\Client::class);
        Passport::useTokenModel(\Laravel\Passport\Token::class);
        Passport::useAuthCodeModel(\Laravel\Passport\AuthCode::class);
        Passport::useRefreshTokenModel(\Laravel\Passport\RefreshToken::class);
    }

    public function testItUsesDefaultClientModelWhenNoneConfigured(): void
    {
        config([
            'passport-ui.models' => [
                'client' => null,
            ],
        ]);

        $this->app->make(PassportModelsBooter::class)->boot();

        self::assertSame(Client::class, Passport::clientModel());
    }

    public function testItUsesConfiguredPassportModels(): void
    {
        config([
            'passport-ui.models' => [
                'client' => Client::class,
                'token' => \Laravel\Passport\Token::class,
                'auth_code' => \Laravel\Passport\AuthCode::class,
                'refresh_token' => \Laravel\Passport\RefreshToken::class,
            ],
        ]);

        $this->app->make(PassportModelsBooter::class)->boot();

        self::assertSame(Client::class, Passport::clientModel());
        self::assertSame(\Laravel\Passport\Token::class, Passport::tokenModel());
        self::assertSame(\Laravel\Passport\AuthCode::class, Passport::authCodeModel());
        self::assertSame(\Laravel\Passport\RefreshToken::class, Passport::refreshTokenModel());
    }

    public function testItSkipsEmptyNonClientModels(): void
    {
        config([
            'passport-ui.models' => [
                'client' => Client::class,
                'token' => null,
                'auth_code' => null,
                'refresh_token' => null,
            ],
        ]);

        $this->app->make(PassportModelsBooter::class)->boot();

        self::assertSame(Client::class, Passport::clientModel());
        self::assertSame(\Laravel\Passport\Token::class, Passport::tokenModel());
        self::assertSame(\Laravel\Passport\AuthCode::class, Passport::authCodeModel());
        self::assertSame(\Laravel\Passport\RefreshToken::class, Passport::refreshTokenModel());
    }
}
