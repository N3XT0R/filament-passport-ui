<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Services\ClientService;

readonly class DeleteClientUseCase
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function execute(Client $client, ?Authenticatable $actor = null): bool
    {
        return $this->clientService->deleteClient($client, $actor);
    }
}
