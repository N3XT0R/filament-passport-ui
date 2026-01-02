<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\OAuth\ValueObjects;

use InvalidArgumentException;

final class Scope
{
    public const string SEPARATOR = ':';

    public function __construct(
        public readonly string $resource,
        public readonly string $action,
    ) {
        self::assertValid($resource, $action);
    }

    public static function fromString(string $scope): self
    {
        [$resource, $action] = explode(self::SEPARATOR, $scope, 2);

        if ($action === null) {
            throw new InvalidArgumentException("Invalid scope format: {$scope}");
        }

        return new self($resource, $action);
    }

    public function toString(): string
    {
        return $this->resource . self::SEPARATOR . $this->action;
    }

    public function equals(self $other): bool
    {
        return $this->resource === $other->resource
            && $this->action === $other->action;
    }

    private static function assertValid(string $resource, string $action): void
    {
        if ($resource === '' || $action === '') {
            throw new InvalidArgumentException('Scope parts must not be empty.');
        }

        if (str_contains($resource, self::SEPARATOR)
            || str_contains($action, self::SEPARATOR)
        ) {
            throw new InvalidArgumentException(
                'Scope parts must not contain the separator.'
            );
        }
    }
}
