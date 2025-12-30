<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Contracts\OAuthenticatable;

class OwnerRepository
{

    private function getBaseQuery(): Builder
    {
        /** @var class-string<Model&OAuthenticatable> $modelClass */
        $modelClass = config('filament-passport-ui.owner_model', 'App\\Models\\User');

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

    public function all(): Collection
    {
        return $this->getBaseQuery()->get();
    }
}
