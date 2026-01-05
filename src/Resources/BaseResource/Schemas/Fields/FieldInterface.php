<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields;

use Filament\Forms\Components\Field;

interface FieldInterface
{
    public static function make(string $name = ''): Field;
}
