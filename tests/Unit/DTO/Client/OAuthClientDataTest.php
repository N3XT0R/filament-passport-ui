<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Unit\DTO\Client;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
use PHPUnit\Framework\TestCase;

final class OAuthClientDataTest extends TestCase
{
    public function testItCreatesDtoWithAllArguments(): void
    {
        $owner = new class () extends Model {
        };

        $dto = new OAuthClientData(
            name: 'Test Client',
            redirectUris: ['https://example.com/callback'],
            provider: 'users',
            confidential: false,
            options: ['foo' => 'bar'],
            revoked: true,
            owner: $owner,
        );

        self::assertSame('Test Client', $dto->name);
        self::assertSame(['https://example.com/callback'], $dto->redirectUris);
        self::assertSame('users', $dto->provider);
        self::assertFalse($dto->confidential);
        self::assertSame(['foo' => 'bar'], $dto->options);
        self::assertTrue($dto->revoked);
        self::assertSame($owner, $dto->owner);
    }

    public function testItCreatesDtoWithDefaultValues(): void
    {
        $dto = new OAuthClientData(name: 'Default Client');

        self::assertSame('Default Client', $dto->name);
        self::assertSame([], $dto->redirectUris);
        self::assertNull($dto->provider);
        self::assertTrue($dto->confidential);
        self::assertSame([], $dto->options);
        self::assertFalse($dto->revoked);
        self::assertNull($dto->owner);
    }

    public function testFromArrayUsesDefaultsForMissingValues(): void
    {
        $dto = OAuthClientData::fromArray([
            'name' => 'Array Client',
        ]);

        self::assertSame('Array Client', $dto->name);
        self::assertSame([], $dto->redirectUris);
        self::assertNull($dto->provider);
        self::assertTrue($dto->confidential);
        self::assertSame([], $dto->options);
        self::assertFalse($dto->revoked);
        self::assertNull($dto->owner);
    }

    public function testFromArrayMapsAllValuesCorrectly(): void
    {
        $owner = new class () extends Model {
        };

        $dto = OAuthClientData::fromArray([
            'name' => 'Array Client',
            'redirect_uris' => ['https://example.com'],
            'provider' => 'users',
            'confidential' => false,
            'options' => ['scope' => 'read'],
            'revoked' => true,
            'owner' => $owner,
        ]);

        self::assertSame('Array Client', $dto->name);
        self::assertSame(['https://example.com'], $dto->redirectUris);
        self::assertSame('users', $dto->provider);
        self::assertFalse($dto->confidential);
        self::assertSame(['scope' => 'read'], $dto->options);
        self::assertTrue($dto->revoked);
        self::assertSame($owner, $dto->owner);
    }

    public function testIsRedirectUrisEmpty(): void
    {
        self::assertTrue(
            new OAuthClientData(name: 'Client')->isRedirectUrisEmpty()
        );

        self::assertFalse(
            new OAuthClientData(
                name: 'Client',
                redirectUris: ['https://example.com']
            )->isRedirectUrisEmpty()
        );
    }

    public function testIsNameEmpty(): void
    {
        self::assertTrue(
            new OAuthClientData(name: '')->isNameEmpty()
        );

        self::assertFalse(
            new OAuthClientData(name: 'Valid')->isNameEmpty()
        );
    }
}
