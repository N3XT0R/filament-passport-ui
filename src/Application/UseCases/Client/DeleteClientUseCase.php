<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\Services\ClientService;

readonly class DeleteClientUseCase
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function execute(Client $client): bool
    {
        return $this->clientService->deleteClient($client);
    }
}
