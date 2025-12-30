<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Laravel\Passport\Contracts\OAuthenticatable;

class ClientService
{

    public function createPersonalAccessClientForUser(OAuthenticatable $user, string $name)
    {
    }
}
