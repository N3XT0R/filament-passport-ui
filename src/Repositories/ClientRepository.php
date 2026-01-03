<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository as BaseRepository;
use Laravel\Passport\Passport;

class ClientRepository extends BaseRepository
{
    /**
     * Get all OAuth clients.
     * @return Collection<Client>
     */
    public function all(): Collection
    {
        return Passport::clientModel()::all();
    }

    /**
     * Find an OAuth client by its name.
     * @param string $name
     * @return Client|null
     */
    public function findByName(string $name): ?Client
    {
        return Passport::clientModel()::where('name', $name)->first();
    }

    /**
     * Count the total number of OAuth clients.
     * @return int
     */
    public function count(): int
    {
        return Passport::clientModel()::count();
    }

    /**
     * Get the last login time for a given client.
     * @param Client $client
     * @return CarbonInterface|null
     */
    public function getLastLoginAtForClient(Client $client): ?CarbonInterface
    {
        return $client->tokens()
            ->orderBy('updated_at', 'desc')
            ->first()
            ?->updated_at;
    }
}
