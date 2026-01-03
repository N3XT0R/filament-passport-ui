<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use Illuminate\Contracts\Container\Container;
use N3XT0R\FilamentPassportUi\Providers\Boot\Concerns\BooterInterface;

abstract class BaseBooter implements BooterInterface
{
    public function __construct(
        protected readonly Container $app
    ) {
    }
}
