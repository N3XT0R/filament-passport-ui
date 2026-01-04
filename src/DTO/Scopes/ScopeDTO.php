<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\DTO\Scopes;

readonly class ScopeDTO
{
    public function __construct(
        public string $scope,
        public bool $isGlobal = false,
        public ?string $resource = null,
        public ?string $description = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            scope: $data['scope'],
            isGlobal: $data['isGlobal'] ?? false,
            resource: $data['resource'] ?? null,
            description: $data['description'] ?? null,
        );
    }
}
