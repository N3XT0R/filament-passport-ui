<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas;

use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\CreatedAtColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\TableInterface;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\GrantTypesColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\LastLoginColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\NameColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\OwnerNameColumn;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns\RevokeColumn;

class ClientResourceTable implements TableInterface
{

    public static function configure(Table $table): Table
    {
        return $table->columns([
            NameColumn::make(),
            OwnerNameColumn::make(),
            GrantTypesColumn::make(),
            LastLoginColumn::make(),
            RevokeColumn::make(),
            CreatedAtColumn::make(),
            CreatedAtColumn::make('updated_at')
                ->label(__('filament-passport-ui::passport-ui.common.updated_at')),
        ]);
    }
}
