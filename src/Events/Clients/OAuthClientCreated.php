<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Events\Clients;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Laravel\Passport\Client;

class OAuthClientCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Client $channel)
    {
    }
}
