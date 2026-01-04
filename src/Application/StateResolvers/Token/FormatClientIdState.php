<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\Token;

use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;

readonly class FormatClientIdState
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function execute(string $state): ?string
    {
        return $this->clientRepository->find($state)?->name;
    }
}
