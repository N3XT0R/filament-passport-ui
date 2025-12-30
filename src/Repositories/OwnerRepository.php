<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Contracts\OAuthenticatable;

class OwnerRepository
{
    /**
     * Find an owner by its primary key.
     * @param $key
     * @return (OAuthenticatable&Model)|null
     */
    public function findOwnerByKey($key): (Model&OAuthenticatable)|null
    {
        /** @var class-string<Model&OAuthenticatable> $modelClass */
        $modelClass = config('filament-passport-ui.owner_model', 'App\\Models\\User');
        /**
         * @var (OAuthenticatable&Model)|null $owner
         */
        $owner = $modelClass::query()->find($key);

        return $owner;
    }
}
