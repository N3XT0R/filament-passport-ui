<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Observers;

use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientRevokedEvent;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;

class ClientObserver extends BaseObserver
{


    /**
     * Handle the Client "updated" event.
     * @param Client $model
     * @return void
     */
    public function updated(Model $model): void
    {
        if ($model->getAttribute('revoked') === true) {
            OAuthClientRevokedEvent::dispatch($model);
        }
    }
}
