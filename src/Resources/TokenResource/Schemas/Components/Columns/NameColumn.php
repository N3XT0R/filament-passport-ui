<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns;

use Filament\Tables\Columns\TextColumn;

class NameColumn
{
    public static function make(string $name = 'name'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.token_resource.column.name'))
            ->sortable()
            ->searchable()
            ->toggleable()
            ->toggledHiddenByDefault();
    }
}
