<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;

/**
 * Clears the action cache when a PassportScopeAction model is created, updated, deleted, restored, or force deleted.
 */
class PassportScopeActionObserver extends BaseObserver
{
    public function created(Model $model): void
    {
        $this->clearCache();
    }

    public function updated(Model $model): void
    {
        $this->clearCache();
    }

    public function deleted(Model $model): void
    {
        $this->clearCache();
    }

    public function restored(Model $model): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Model $model): void
    {
        $this->clearCache();
    }

    /**
     * Clear cached scope actions if caching is enabled.
     */
    protected function clearCache(): void
    {
        $repository = app(ActionRepositoryContract::class);

        if ($repository instanceof CachedActionRepositoryDecorator) {
            $repository->clearCache();
        }
    }
}
