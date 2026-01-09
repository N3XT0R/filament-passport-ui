<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;

final readonly class ImplicitGrantClientStrategy extends BaseStrategy
{
    public function supports(OAuthClientType $type): bool
    {
        return $type === OAuthClientType::IMPLICIT;
    }

    public function create(
        string $name,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        array $options = []
    ): Client {
        return $this->clients->createImplicitGrantClient(
            $name,
            $redirectUris
        );
    }
}
