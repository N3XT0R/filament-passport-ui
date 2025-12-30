<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Exceptions\Domain;

use DomainException;

class ClientAlreadyExists extends DomainException
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('OAuth client with name "%s" already exists.', $name)
        );
    }
}
