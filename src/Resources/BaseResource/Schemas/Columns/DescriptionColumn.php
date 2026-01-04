<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns;

use Filament\Tables\Columns\TextColumn;

class DescriptionColumn implements ColumnInterface
{
    public static function make(string $name = 'description'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.description'))
            ->sortable()
            ->searchable();
    }
}
