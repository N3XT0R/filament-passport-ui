<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Laravel\Passport\Client;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\FilamentPassportUi\Exceptions\Domain\ClientAlreadyExists;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;

readonly class ClientService
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    /**
     * Create a personal access client for the given user with the specified name.
     * @param OAuthenticatable $user
     * @param string $name
     * @return Client
     * @throws ClientAlreadyExists|\Throwable
     */
    public function createPersonalAccessClientForUser(OAuthenticatable $user, string $name): Client
    {
        if ($this->clientRepository->findByName($name)) {
            throw new ClientAlreadyExists($name);
        }


        $client = $this->clientRepository->createPersonalAccessGrantClient($name);
        $client->owner()->associate($user);
        $client->saveOrFail();

        return $client;
    }
}
