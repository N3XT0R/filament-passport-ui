<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\FilamentPassportUi\DTO\Client\CreateOAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Exceptions\Domain\ClientAlreadyExists;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactoryInterface;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use Throwable;

readonly class ClientService
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    /**
     * Create a new OAuth client for the given user
     * @param OAuthClientType $type
     * @param CreateOAuthClientData $data
     * @param OAuthenticatable|null $user
     * @return Client
     * @throws Throwable
     */
    public function createClientForUser(
        OAuthClientType $type,
        CreateOAuthClientData $data,
        ?OAuthenticatable $user,
    ): Client {
        if ($this->clientRepository->findByName($data->name)) {
            throw new ClientAlreadyExists($data->name);
        }

        $factory = app(OAuthClientFactoryInterface::class);
        $client = $factory($type, $data, $user);

        $client->owner()->associate($user);
        $client->saveOrFail();

        activity('oauth')
            ->performedOn($client)
            ->causedBy($user)
            ->withProperties([
                'name' => $client->getAttribute('name'),
                'grant_types' => $client->getAttribute('grant_types'),
                'type' => $type->value,
            ])
            ->log('OAuth client created');

        return $client;
    }

    /**
     * Change the owner of the given client to the new owner
     * @param Client $client
     * @param OAuthenticatable $newOwner
     * @param Authenticatable|null $actor
     * @return Client
     * @throws Throwable
     */
    public function changeOwnerOfClient(
        Client $client,
        OAuthenticatable $newOwner,
        ?Authenticatable $actor = null
    ): Client {
        $client->owner()->associate($newOwner);
        $client->saveOrFail();

        activity('oauth')
            ->performedOn($client)
            ->causedBy($actor)
            ->withProperties([
                'name' => $client->getAttribute('name'),
                'new_owner_id' => $newOwner->getAuthIdentifier(),
            ])
            ->log('OAuth client ownership changed');

        return $client;
    }

    /**
     * get the label attribute of the owner of the given client
     * @param Client|string|int $client
     * @return string|null
     */
    public function getOwnerLabelAttribute(Client|string|int $client): ?string
    {
        if (!$client instanceof Client) {
            $client = $this->clientRepository->find($client);

            if ($client === null) {
                return null;
            }
        }
        $owner = $client->owner;

        if ($owner === null) {
            return null;
        }

        $labelAttribute = app(ConfigRepository::class)->getOwnerLabelAttribute();

        if ($client->hasAttribute($labelAttribute)) {
            return (string)$owner->getAttribute($labelAttribute);
        }

        return null;
    }
}
