<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Illuminate\Support\Collection;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

class ClientRepository
{
    /**
     * @return Collection<Client>
     */
    public function all(): Collection
    {
        return Passport::clientModel()::all();
    }
}
