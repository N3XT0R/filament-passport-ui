<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Repositories\OwnerRepository;
use N3XT0R\FilamentPassportUi\Services\ClientService;
use N3XT0R\FilamentPassportUi\Services\GrantService;

/**
 * Use case to create a new OAuth client
 */
readonly class CreateClientUseCase
{
    public function __construct(
        private OwnerRepository $ownerRepository,
        private ClientService $clientService,
        private GrantService $grantService,
    ) {
    }

    /**
     * Create a new OAuth client
     * @param array $data
     * @param Authenticatable|null $actor
     * @return Client
     * @throws \Throwable
     */
    public function execute(array $data, ?Authenticatable $actor = null): Client
    {
        $owner = $data['owner'] ?? null;

        if ($owner instanceof OAuthenticatable === false) {
            $owner = $this->ownerRepository->findByKey($owner);
        }
        $data['owner'] = $owner;

        $dto = OAuthClientData::fromArray($data);
        $scopes = $data['scopes'] ?? [];


        $client = $this->clientService->createClientForUser(
            type: OAuthClientType::from($data['grant_type']),
            data: $dto,
            actor: $actor
        );

        $this->grantService->giveGrantsToTokenable(
            tokenable: $client,
            scopes: $scopes,
            actor: $actor
        );


        return $client;
    }
}
