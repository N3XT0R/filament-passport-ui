<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Components;

use Filament\Schemas\Components\Component;

interface ComponentInterface
{
    public static function make(string $name = ''): Component;
}