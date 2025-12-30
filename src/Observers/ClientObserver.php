<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use Illuminate\Database\Eloquent\Model;

class ClientObserver extends BaseObserver
{
    public function created(Model $model): void
    {
    }
}
