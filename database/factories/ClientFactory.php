<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;

class ClientFactory extends \Laravel\Passport\Database\Factories\ClientFactory
{
    protected $model = Client::class;


    public function withPassportScopeGrants(array $scopeGrants): self
    {
        return $this->afterCreating(function (Client $client) use ($scopeGrants) {
            foreach ($scopeGrants as $scopeGrant) {
                $this->withPassportScopeGrant($scopeGrant);
            }
        });
    }

    public function withPassportScopeGrant(PassportScopeGrant $grant): self
    {
        return $this->afterCreating(function (Client $client) use ($grant) {
            $client->passportScopeGrants()->save($grant);
        });
    }
}