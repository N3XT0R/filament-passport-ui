<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Register\Concerns;

interface RegistrarInterface
{
    /**
     * Register bindings in the container.
     * @return void
     */
    public function register(): void;
}
