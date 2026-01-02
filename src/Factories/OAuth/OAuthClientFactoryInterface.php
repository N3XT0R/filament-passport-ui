<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;

interface OAuthClientFactoryInterface
{
    public function __invoke(
        OAuthClientType $type,
        string $name,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        array $options = []
    ): Client;
}
