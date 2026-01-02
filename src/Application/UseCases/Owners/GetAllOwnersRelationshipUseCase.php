<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;

readonly class GetAllOwnersRelationshipUseCase extends GetAllOwnersUseCase
{
    public function __construct(private ConfigRepository $configRepository)
    {
    }

    /**
     * Get All Owners as relationship options
     * @return Collection
     */
    public function execute(): Collection
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = $this->configRepository->getOwnerModel();
        $keyName = new $modelClass()->getKeyName();

        return parent::execute()->pluck(
            $this->configRepository->getOwnerLabelAttribute(),
            $keyName,
        );
    }
}
