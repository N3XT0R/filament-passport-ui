<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Factories;

use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Database\Factories\ClientFactory as BaseClientFactory;

class ClientFactory extends BaseClientFactory
{
    protected $model = Client::class;
}