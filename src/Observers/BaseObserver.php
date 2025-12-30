<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;


use Illuminate\Database\Eloquent\Model;

abstract class BaseObserver
{
    public function retrieved(Model $model): void
    {
    }

    public function creating(Model $model): void
    {
    }

    public function created(Model $model): void
    {
    }

    public function updating(Model $model): void
    {
    }

    public function updated(Model $model): void
    {
    }

    public function saving(Model $model): void
    {
    }

    public function saved(Model $model): void
    {
    }

    public function deleting(Model $model)
    {
    }

    public function deleted(Model $model): void
    {
    }

    public function restoring(Model $model): void
    {
    }

    public function restored(Model $model): void
    {
    }

    public function forceDeleting(Model $model): void
    {
    }

    public function forceDeleted(Model $model): void
    {
    }

    public function replicating(Model $model): void
    {
    }
}
