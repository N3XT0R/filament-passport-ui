<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Events\PassportScopeResource;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

abstract class BaseResourceEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public PassportScopeResource $resource, public ?Authenticatable $actor = null)
    {
    }
}