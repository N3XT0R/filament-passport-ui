<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\DTO\Client\CreateOAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Services\ClientService;

readonly class CreateClientUseCase
{
    public function __construct(
        private ClientService $clientService
    ) {
    }

    public function execute(array $data, ?Authenticatable $actor = null): void
    {
        $clientType = OAuthClientType::from($data['grant_type']);
        $dto = CreateOAuthClientData::fromArray($data);


        $this->clientService->createClientForUser(
            type: $clientType,
            data: $dto,
            user: $actor
        );
    }
}
