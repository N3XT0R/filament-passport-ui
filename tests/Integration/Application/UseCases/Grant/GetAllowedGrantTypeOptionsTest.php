<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\UseCases\Grant;

use N3XT0R\FilamentPassportUi\Application\UseCases\Grant\GetAllowedGrantTypeOptions;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;

final class GetAllowedGrantTypeOptionsTest extends DatabaseTestCase
{
    private GetAllowedGrantTypeOptions $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllowedGrantTypeOptions::class);
    }

    public function testExecuteReturnsConfiguredGrantTypesAsOptions(): void
    {
        config([
            'passport-authorization-core.oauth.allowed_grant_types' => [
                OAuthClientType::AUTHORIZATION_CODE->value,
                OAuthClientType::CLIENT_CREDENTIALS->value,
            ],
        ]);

        $options = $this->useCase->execute();

        self::assertSame(
            [
                OAuthClientType::AUTHORIZATION_CODE->value => 'Authorization code',
                OAuthClientType::CLIENT_CREDENTIALS->value => 'Client credentials',
            ],
            $options->all()
        );
    }
}
