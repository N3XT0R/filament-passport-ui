<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;

class PassportScopeResourceObserver
{

    public function __call($method, array $arguments): void
    {
        if (in_array($method, ['created', 'updated', 'deleted', 'restored', 'forceDeleted'], true)) {
            $repository = app(ResourceRepositoryContract::class);
            if ($repository instanceof CachedResourceRepositoryDecorator) {
                $repository->clearCache();
            }
        }
    }
}
