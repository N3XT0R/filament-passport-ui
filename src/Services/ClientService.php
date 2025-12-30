<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Laravel\Passport\Client;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;

readonly class ClientService
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function createPersonalAccessClientForUser(OAuthenticatable $user, string $name): ?Client
    {
        if ($this->clientRepository->findByName($name)) {
            return null;
        }


        $client = $this->clientRepository->createPersonalAccessGrantClient($name);
        $client->owner()->associate($user);
        return $client->save() ? $client : null;
    }
}
