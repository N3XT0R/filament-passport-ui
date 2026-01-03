<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientCreated;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;

class ClientObserver extends BaseObserver
{
    public function created(Model $model): void
    {
        OAuthClientCreated::dispatch($model);
    }

    public function deleting(Model $model): void
    {
        app(ClientRepository::class)->delete($model, true);
    }
}
