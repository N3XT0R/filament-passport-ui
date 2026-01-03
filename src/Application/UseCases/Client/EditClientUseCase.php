<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Services\ClientService;
use N3XT0R\FilamentPassportUi\Services\GrantService;

readonly class EditClientUseCase
{
    public function __construct(
        private ClientService $clientService,
        private ClientRepository $clientRepository,
        private GrantService $grantService,
    ) {
    }

    public function execute(Client $client, array $data, ?Authenticatable $actor = null): Client
    {
        $client->
        $this->clientRepository->update($client);
    }
}
