<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Events\Clients\OauthClientDeletedEvent;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\FilamentPassportUi\Services\ClientService;

/**
 * Use case to delete an OAuth client
 */
readonly class DeleteClientUseCase
{
    public function __construct(
        private ClientService $clientService,
        private ScopeGrantRepository $scopeGrantRepository
    ) {
    }

    public function execute(Client $client, ?Authenticatable $actor = null): bool
    {
        $this->scopeGrantRepository->deleteAllGrantsForTokenable($client);
        $result = $this->clientService->deleteClient($client, $actor);
        OauthClientDeletedEvent::dispatch($client, $actor);

        return $result;
    }
}
