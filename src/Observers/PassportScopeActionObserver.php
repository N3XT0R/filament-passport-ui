<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;

/**
 * Clears the action cache when a PassportScopeAction model is created, updated, deleted, restored, or force deleted.
 */
class PassportScopeActionObserver extends BaseObserver
{

    public function __call($method, $arguments)
    {
        static::__callStatic($method, $arguments);
    }


    public static function __callStatic($method, $arguments)
    {
        if (in_array($method, ['created', 'updated', 'deleted', 'restored', 'forceDeleted'], true)) {
            $repository = app(ActionRepositoryContract::class);
            if ($repository instanceof CachedActionRepositoryDecorator) {
                $repository->clearCache();
            }
        }
    }
}
