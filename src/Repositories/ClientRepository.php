<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Illuminate\Support\Collection;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository as BaseRepository;

class ClientRepository extends BaseRepository
{
    /**
     * @return Collection<Client>
     */
    public function all(): Collection
    {
        return Passport::clientModel()::all();
    }

    public function findByName(string $name): ?Client
    {
        return Passport::clientModel()::where('name', $name)->first();
    }
}
