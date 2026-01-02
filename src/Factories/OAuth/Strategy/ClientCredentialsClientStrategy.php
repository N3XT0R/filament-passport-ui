<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;

final readonly class ClientCredentialsClientStrategy implements OAuthClientCreationStrategyInterface
{
    public function __construct(
        private ClientRepository $clients
    ) {
    }

    public function supports(OAuthClientType $type): bool
    {
        return $type === OAuthClientType::CLIENT_CREDENTIALS;
    }

    public function create(
        string $name,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        array $options = []
    ): Client {
        return $this->clients->createClientCredentialsGrantClient($name);
    }
}
