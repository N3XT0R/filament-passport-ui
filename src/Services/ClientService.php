<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Laravel\Passport\Client;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\FilamentPassportUi\Exceptions\Domain\ClientAlreadyExists;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use Throwable;

readonly class ClientService
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    /**
     * Create a personal access client for the given user with the specified name.
     * @param OAuthenticatable $user The user to associate the client with
     * @param string $name Name of the personal access client
     * @param string|null $provider Optional user provider
     * @return Client
     * @throws ClientAlreadyExists|Throwable
     */
    public function createPersonalAccessClientForUser(
        OAuthenticatable $user,
        string $name,
        ?string $provider = null
    ): Client {
        if ($this->clientRepository->findByName($name)) {
            throw new ClientAlreadyExists($name);
        }


        $client = $this->clientRepository->createPersonalAccessGrantClient($name, $provider);
        $client->owner()->associate($user);
        $client->saveOrFail();

        activity('oauth')
            ->performedOn($client)
            ->causedBy($user)
            ->withProperties([
                'name' => $client->getAttribute('name'),
                'grant_types' => $client->getAttribute('grant_types'),
            ])
            ->log('OAuth client created');

        return $client;
    }
}
