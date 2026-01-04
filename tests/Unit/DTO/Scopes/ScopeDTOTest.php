<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Unit\DTO\Scopes;

use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use PHPUnit\Framework\TestCase;

final class ScopeDTOTest extends TestCase
{
    public function testItCreatesDtoWithAllArguments(): void
    {
        $dto = new ScopeDTO(
            scope: 'users:read',
            isGlobal: true,
            resource: 'users',
            description: 'Read access to users'
        );

        self::assertSame('users:read', $dto->scope);
        self::assertTrue($dto->isGlobal);
        self::assertSame('users', $dto->resource);
        self::assertSame('Read access to users', $dto->description);
    }

    public function testItCreatesDtoWithDefaultValues(): void
    {
        $dto = new ScopeDTO(scope: 'users:write');

        self::assertSame('users:write', $dto->scope);
        self::assertFalse($dto->isGlobal);
        self::assertNull($dto->resource);
        self::assertNull($dto->description);
    }

    public function testFromArrayMapsAllValuesCorrectly(): void
    {
        $dto = ScopeDTO::fromArray([
            'scope' => 'posts:read',
            'isGlobal' => true,
            'resource' => 'posts',
            'description' => 'Read blog posts',
        ]);

        self::assertSame('posts:read', $dto->scope);
        self::assertTrue($dto->isGlobal);
        self::assertSame('posts', $dto->resource);
        self::assertSame('Read blog posts', $dto->description);
    }

    public function testFromArrayUsesDefaultsForMissingOptionalValues(): void
    {
        $dto = ScopeDTO::fromArray([
            'scope' => 'comments:read',
        ]);

        self::assertSame('comments:read', $dto->scope);
        self::assertFalse($dto->isGlobal);
        self::assertNull($dto->resource);
        self::assertNull($dto->description);
    }
}
