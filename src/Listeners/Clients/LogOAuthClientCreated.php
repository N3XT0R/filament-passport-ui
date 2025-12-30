<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Listeners\Clients;

use N3XT0R\FilamentPassportUi\Events\Clients\OAuthClientCreated;

class LogOAuthClientCreated
{
    public function handle(OAuthClientCreated $event): void
    {
        activity('oauth')
            ->performedOn($event->client)
            //->causedBy($event->actor)
            ->withProperties([
                'name' => $event->client->name,
            ])
            ->log('OAuth client created');
    }
}
