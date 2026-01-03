<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;

class PassportScopeActionObserver extends BaseObserver
{
    public function __call($method, array $arguments): void
    {
        if (in_array($method, ['created', 'updated', 'deleted', 'restored', 'forceDeleted'], true)) {
            $repository = app(ActionRepositoryContract::class);
            if ($repository instanceof CachedActionRepositoryDecorator) {
                $repository->clearCache();
            }
        }
    }
}
