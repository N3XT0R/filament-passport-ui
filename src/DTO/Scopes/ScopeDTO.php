<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\DTO\Scopes;

class ScopeDTO
{
    public function __construct(
        public readonly string $scope,
        public readonly ?string $resource = null,
        public readonly ?string $description = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            scope: $data['scope'],
            resource: $data['resource'] ?? null,
            description: $data['description'] ?? null,
        );
    }
}
