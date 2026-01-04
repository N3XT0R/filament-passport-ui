<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns;

use Filament\Tables\Columns\TextColumn;

class NameColumn implements ColumnInterface
{
    public static function make(string $name = 'name'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.name'))
            ->sortable()
            ->searchable();
    }
}
