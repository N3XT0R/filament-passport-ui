<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns;

use Filament\Tables\Columns\IconColumn;

class IsActiveColumn implements ColumnInterface
{
    public static function make(string $name = 'is_active'): IconColumn
    {
        return IconColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.is_active'))
            ->boolean();
    }
}
