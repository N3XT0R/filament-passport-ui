<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Services\ClientService;
use N3XT0R\FilamentPassportUi\Services\GrantService;

/**
 * Use case to edit an existing OAuth client
 */
readonly class EditClientUseCase
{
    public function __construct(
        private ClientService $clientService,
        private GrantService $grantService,
    ) {
    }

    /**
     * Edit an existing OAuth client
     * @param Client $client
     * @param array $data
     * @param Authenticatable|null $actor
     * @return Client
     */
    public function execute(Client $client, array $data, ?Authenticatable $actor = null): Client
    {
        $dto = new OAuthClientData(
            name: $data['name'],
            redirectUris: $data['redirect_uris'] ?? [],
            provider: $data['provider'] ?? null,
            confidential: $data['confidential'] ?? true,
            options: $data['options'] ?? [],
            revoked: $data['revoked'] ?? false,
            owner: $data['owner'] ?? null,
        );
        $scopes = $data['scopes'] ?? [];

        $client = $this->clientService->updateClient($client, $dto, $actor);
        $this->grantService->upsertGrantsForTokenable(
            tokenable: $client,
            scopes: $scopes,
            actor: $actor
        );

        return $client;
    }
}
