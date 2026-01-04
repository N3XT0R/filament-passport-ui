<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\Token;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Services\ClientService;

readonly class FormatUserIdState
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function execute(Model $record): ?string
    {
        return $this->clientService->getOwnerLabelAttribute(
            $record->getAttribute('client_id') ?? ''
        );
    }
}
