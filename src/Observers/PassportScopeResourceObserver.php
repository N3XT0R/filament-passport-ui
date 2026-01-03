<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;

/**
 * Clears the resource cache when a PassportScopeResource model is created, updated, deleted, restored, or force deleted.
 */
class PassportScopeResourceObserver extends BaseObserver
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
     * Clear cached scope resources if caching is enabled.
     */
    protected function clearCache(): void
    {
        $repository = app(ResourceRepositoryContract::class);

        if ($repository instanceof CachedResourceRepositoryDecorator) {
            $repository->clearCache();
        }
    }
}
