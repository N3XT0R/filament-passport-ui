<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy;

use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Auth\Authenticatable;

final readonly class PersonalAccessClientStrategy implements OAuthClientCreationStrategyInterface
{
    public function __construct(
        private ClientRepository $clients
    ) {
    }

    public function supports(OAuthClientType $type): bool
    {
        return $type === OAuthClientType::PERSONAL_ACCESS;
    }

    public function create(
        string $name,
        array $redirectUris = [],
        ?string $provider = null,
        bool $confidential = true,
        ?Authenticatable $user = null,
        array $options = []
    ): Client {
        return $this->clients->createPersonalAccessGrantClient(
            $name,
            $provider
        );
    }
}
