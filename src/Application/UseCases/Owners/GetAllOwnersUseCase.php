<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Repositories\OwnerRepository;

readonly class GetAllOwnersUseCase
{
    public function __construct(private OwnerRepository $ownerRepository)
    {
    }

    /**
     * Get All Owners
     * @return Collection
     */
    public function execute(): Collection
    {
        return $this->ownerRepository->all();
    }
}
