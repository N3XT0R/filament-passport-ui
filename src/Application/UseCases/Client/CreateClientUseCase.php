<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\DTO\Client\CreateOAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Services\ClientService;
use N3XT0R\FilamentPassportUi\Services\GrantService;

readonly class CreateClientUseCase
{
    public function __construct(
        private ClientService $clientService,
        private GrantService $grantService,
    ) {
    }

    public function execute(array $data, ?Authenticatable $actor = null): void
    {
        $clientType = OAuthClientType::from($data['grant_type']);
        $dto = CreateOAuthClientData::fromArray($data);
        $scopes = $data['scopes'] ?? [];


        $client = $this->clientService->createClientForUser(
            type: $clientType,
            data: $dto,
            user: $actor
        );
    }
}
