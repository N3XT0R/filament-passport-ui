<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns;

use Filament\Tables\Columns\TextColumn;

class IdColumn implements ColumnInterface
{

    public static function make(string $name = 'id'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.id'))
            ->sortable();
    }
}
