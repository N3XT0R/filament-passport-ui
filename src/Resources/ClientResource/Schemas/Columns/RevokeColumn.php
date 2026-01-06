<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns;

use Filament\Tables\Columns\IconColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\IsActiveColumn;

class RevokeColumn extends IsActiveColumn
{
    public static function make(string $name = 'revoked'): IconColumn
    {
        return parent::make($name)
            ->label(__('filament-passport-ui::passport-ui.client_resource.column.revoked'))
            ->toggleable();
    }
}
