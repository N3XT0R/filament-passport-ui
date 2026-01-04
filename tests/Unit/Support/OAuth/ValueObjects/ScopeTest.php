<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Unit\Support\OAuth\ValueObjects;

use InvalidArgumentException;
use N3XT0R\FilamentPassportUi\Support\OAuth\ValueObjects\Scope;
use PHPUnit\Framework\TestCase;

final class ScopeTest extends TestCase
{
    public function testItCreatesScopeFromParts(): void
    {
        $scope = new Scope('users', 'read');

        self::assertSame('users', $scope->resource);
        self::assertSame('read', $scope->action);
    }

    public function testItCreatesScopeFromString(): void
    {
        $scope = Scope::fromString('users:read');

        self::assertSame('users', $scope->resource);
        self::assertSame('read', $scope->action);
    }

    public function testToStringReturnsCorrectFormat(): void
    {
        $scope = new Scope('users', 'update');

        self::assertSame('users:update', $scope->toString());
    }

    public function testEqualsReturnsTrueForSameScope(): void
    {
        $a = new Scope('users', 'read');
        $b = new Scope('users', 'read');

        self::assertTrue($a->equals($b));
    }

    public function testEqualsReturnsFalseForDifferentScope(): void
    {
        $a = new Scope('users', 'read');
        $b = new Scope('users', 'update');

        self::assertFalse($a->equals($b));
    }

    public function testFromStringThrowsExceptionForMissingAction(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Scope::fromString('users');
    }

    public function testConstructorThrowsExceptionForEmptyParts(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Scope('', 'read');
    }

    public function testConstructorThrowsExceptionWhenActionIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Scope('users', '');
    }

    public function testConstructorThrowsExceptionWhenSeparatorIsInResource(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Scope('users:admin', 'read');
    }

    public function testConstructorThrowsExceptionWhenSeparatorIsInAction(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Scope('users', 'read:all');
    }
}
