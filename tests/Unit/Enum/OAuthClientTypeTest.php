<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Unit\Enum;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use PHPUnit\Framework\TestCase;

final class OAuthClientTypeTest extends TestCase
{
    public function testCasesContainAllExpectedValues(): void
    {
        $cases = OAuthClientType::cases();

        self::assertCount(6, $cases);

        self::assertSame([
            OAuthClientType::AUTHORIZATION_CODE,
            OAuthClientType::CLIENT_CREDENTIALS,
            OAuthClientType::PASSWORD,
            OAuthClientType::PERSONAL_ACCESS,
            OAuthClientType::IMPLICIT,
            OAuthClientType::DEVICE,
        ], $cases);
    }

    public function testValuesReturnsAllEnumValues(): void
    {
        self::assertSame(
            [
                'authorization_code',
                'client_credentials',
                'password',
                'personal_access',
                'implicit',
                'device',
            ],
            OAuthClientType::values()
        );
    }

    public function testAllReturnsEnumCases(): void
    {
        self::assertSame(
            OAuthClientType::cases(),
            OAuthClientType::all()
        );
    }

    public function testEnumCanBeCreatedFromValue(): void
    {
        $type = OAuthClientType::from('password');

        self::assertSame(OAuthClientType::PASSWORD, $type);
    }
}
