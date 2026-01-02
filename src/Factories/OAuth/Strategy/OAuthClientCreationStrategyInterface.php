<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use Laravel\Passport\Client;
use Illuminate\Contracts\Auth\Authenticatable;

interface OAuthClientCreationStrategyInterface
{
    public function supports(OAuthClientType $type): bool;

    public function create(
        string $name,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        array $options = []
    ): Client;
}
