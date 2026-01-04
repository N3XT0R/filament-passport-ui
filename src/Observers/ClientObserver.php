<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientCreated;
use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientRevoked;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ScopeGrantRepository;

class ClientObserver extends BaseObserver
{
    public function created(Model $model): void
    {
        OAuthClientCreated::dispatch($model);
    }

    public function deleting(Model $model): void
    {
        app(ClientRepository::class)->delete($model);
        app(ScopeGrantRepository::class)->deleteAllGrantsForTokenable($model);
    }

    /**
     * @param Client $model
     * @return void
     */
    public function updated(Model $model): void
    {
        if ($model->getAttribute('revoked') === true) {
            OAuthClientRevoked::dispatch($model);
        }
    }
}
