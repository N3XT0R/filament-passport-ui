<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\ScopeAction;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;

class FormatIsGlobalState
{
    public function execute(PassportScopeAction $action): bool
    {
        return $action->resource_id === null;
    }
}
