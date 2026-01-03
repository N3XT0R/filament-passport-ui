<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\DTO\Client;

use Illuminate\Database\Eloquent\Model;

final readonly class OAuthClientData
{
    public function __construct(
        public string $name,
        public array $redirectUris = [],
        public ?string $provider = null,
        public bool $confidential = true,
        public array $options = [],
        public bool $revoked = false,
        public ?Model $owner = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            redirectUris: $data['redirect_uris'] ?? [],
            provider: $data['provider'] ?? null,
            confidential: $data['confidential'] ?? true,
            options: $data['options'] ?? [],
            revoked: $data['revoked'] ?? false,
            owner: $data['owner'] ?? null,
        );
    }

    public function isRedirectUrisEmpty(): bool
    {
        return empty($this->redirectUris);
    }

    public function isNameEmpty(): bool
    {
        return empty($this->name);
    }
}
