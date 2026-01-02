<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Repositories\OwnerRepository;

readonly class GetAllOwnersUseCase
{
    /**
     * Get All Owners
     * @return Collection
     */
    public function execute(): Collection
    {
        return app(OwnerRepository::class)->all();
    }
}
