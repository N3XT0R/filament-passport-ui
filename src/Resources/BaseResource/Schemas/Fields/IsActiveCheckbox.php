<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields;

use Filament\Forms\Components\Checkbox;

class IsActiveCheckbox implements FieldInterface
{

    public static function make(string $name = 'is_active'): Checkbox
    {
        return Checkbox::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.is_active'))
            ->default(true);
    }
}
