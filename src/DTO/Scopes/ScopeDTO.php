<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\DTO\Scopes;

class ScopeDTO
{
    public function __construct(
        public readonly string $scope,
        public readonly ?string $description = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            scope: $data['scope'],
            description: $data['description'] ?? null,
        );
    }

    public static function fromStrings(string $scope, ?string $description = null): self
    {
        return new self(
            scope: $scope,
            description: $description,
        );
    }
}
