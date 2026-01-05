<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns;

use Filament\Tables\Columns\TextColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\ColumnInterface;

class OwnerNameColumn implements ColumnInterface
{

    public static function make(string $name = 'owner.name'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.client_resource.column.owner'))
            ->searchable();
    }
}
