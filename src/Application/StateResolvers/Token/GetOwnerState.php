<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\Token;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;

class GetOwnerState
{

    public function __construct(protected OwnerRepository $ownerRepository)
    {
    }

    public function execute(string|int|null $ownerId): ?Model
    {
        if ($ownerId === null) {
            return null;
        }
        return $this->ownerRepository->findByKey($ownerId);
    }
}