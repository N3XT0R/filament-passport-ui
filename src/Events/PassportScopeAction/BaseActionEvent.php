<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Events\PassportScopeAction;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;

abstract class BaseActionEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public PassportScopeAction $client, public ?Authenticatable $actor = null)
    {
    }
}