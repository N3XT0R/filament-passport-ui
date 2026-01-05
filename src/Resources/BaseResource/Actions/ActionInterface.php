<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Actions;

use Filament\Actions\Action;

interface ActionInterface
{
    /**
     * Create a new Action instance.
     * @param string $name
     * @return Action
     */
    public static function make(string $name = ''): Action;
}
