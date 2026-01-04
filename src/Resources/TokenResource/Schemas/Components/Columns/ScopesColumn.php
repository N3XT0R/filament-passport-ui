<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns;

use Filament\Tables\Columns\TextColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\ColumnInterface;

class ScopesColumn implements ColumnInterface
{
    public static function make(string $name = 'scopes'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.token_resource.column.scopes'))
            ->listWithLineBreaks()
            ->sortable()
            ->searchable()
            ->toggleable()
            ->toggledHiddenByDefault();
    }
}
