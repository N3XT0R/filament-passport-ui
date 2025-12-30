<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Events\Scopes;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScopeDeactivated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
}
