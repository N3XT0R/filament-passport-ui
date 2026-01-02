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
}
