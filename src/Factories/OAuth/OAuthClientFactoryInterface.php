<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Factories\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\DTO\Client\CreateOAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;

interface OAuthClientFactoryInterface
{
    public function __invoke(
        OAuthClientType $type,
        CreateOAuthClientData $data,
        ?Authenticatable $user = null,
    ): Client;
}
