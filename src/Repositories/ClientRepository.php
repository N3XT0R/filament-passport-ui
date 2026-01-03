<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository as BaseRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

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


    /**
     * Revoke the given client and all associated tokens.
     *
     * @note Separation of Concerns:
     * This method intentionally overrides the deprecated Passport implementation.
     *
     * In the context of this package, client revocation is a domain-level lifecycle
     * operation. Revoking a client without revoking its access and refresh tokens
     * would lead to an inconsistent security state.
     *
     * While Laravel Passport deprecates this method on the framework layer,
     * this repository keeps it as an explicit aggregate operation to avoid
     * leaking token-revocation logic into higher layers (services, controllers, UI).
     * @param Client $client
     * @param bool $forceDelete
     * @return void
     */
    public function delete(Client $client, bool $forceDelete = false): void
    {
        $client->tokens()->with('refreshToken')->each(function (Token $token): void {
            $token->refreshToken?->revoke();
            $token->revoke();
        });

        if ($forceDelete) {
            $client->delete();
        } else {
            $client->forceFill(['revoked' => true])->save();
        }
    }


    /**
     * Update the given OAuth client.
     *
     * @note Separation of Concerns:
     * This method intentionally overrides the deprecated Passport implementation.
     *
     * Updating a client’s core attributes (name and redirect URIs) is part of the
     * client aggregate lifecycle and must remain a single, coherent operation.
     *
     * Deprecating this method at the framework level would require higher layers
     * (services, controllers, or UI) to reimplement schema-aware update logic,
     * which would violate separation of concerns and risk inconsistent updates
     * across Passport schema versions.
     *
     * This repository keeps the update operation centralized to:
     * - handle schema differences (`redirect` vs. `redirect_uris`)
     * - preserve backward compatibility
     * - ensure consistent client state management
     *
     *
     * @param Client $client
     * @param string $name
     * @param string[] $redirectUris
     * @return bool
     */
    public function update(Client $client, string $name, array $redirectUris): bool
    {
        $columns = $client->getConnection()->getSchemaBuilder()->getColumnListing($client->getTable());

        return $client->forceFill([
            'name' => $name,
            ...(in_array('redirect_uris', $columns) ? [
                'redirect_uris' => $redirectUris,
            ] : [
                'redirect' => implode(',', $redirectUris),
            ]),
        ])->save();
    }
}
