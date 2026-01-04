<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Contracts\OAuthenticatable;

readonly class OwnerRepository
{

    public function __construct(private ConfigRepository $configRepository)
    {
    }

    /**
     * Get the base query for the owner model.
     * @return Builder
     */
    private function getBaseQuery(): Builder
    {
        /** @var class-string<Model&OAuthenticatable> $modelClass */
        $modelClass = $this->configRepository->getOwnerModel();
        return $modelClass::query();
    }

    /**
     * Find an owner by its primary key.
     * @param $key
     * @return (OAuthenticatable&Model)|null
     */
    public function findByKey($key): (Model&OAuthenticatable)|null
    {
        /**
         * @var (OAuthenticatable&Model)|null $owner
         */
        $owner = $this->getBaseQuery()->find($key);

        return $owner;
    }

    /**
     * Get all owners.
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getBaseQuery()->get();
    }
}
