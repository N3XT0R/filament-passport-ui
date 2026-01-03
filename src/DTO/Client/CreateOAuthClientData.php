<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\DTO\Client;

final readonly class CreateOAuthClientData
{
    public function __construct(
        public string $name,
        public array $redirectUris = [],
        public ?string $provider = null,
        public bool $confidential = true,
        public array $options = []
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            redirectUris: $data['redirect_uris'] ?? [],
            provider: $data['provider'] ?? null,
            confidential: $data['confidential'] ?? true,
            options: $data['options'] ?? []
        );
    }
}
