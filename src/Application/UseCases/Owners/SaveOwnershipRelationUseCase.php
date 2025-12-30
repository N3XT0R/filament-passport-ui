<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Owners;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\OwnerRepository;
use N3XT0R\FilamentPassportUi\Services\ClientService;

readonly class SaveOwnershipRelationUseCase
{
    public function __construct(private OwnerRepository $ownerRepository, private ClientService $clientService)
    {
    }

    /**
     * Save ownership relation between client and owner
     * @param Client $client
     * @param int $ownerId
     * @param Authenticatable|null $actor
     * @return void
     * @throws \Throwable
     */
    public function execute(Client $client, int $ownerId, ?Authenticatable $actor = null): void
    {
        $owner = $this->ownerRepository->findByKey($ownerId);
        if ($owner === null) {
            throw new \InvalidArgumentException("Owner with ID {$ownerId} not found.");
        }

        $this->clientService->changeOwnerOfClient($client, $owner, $actor);
    }
}
