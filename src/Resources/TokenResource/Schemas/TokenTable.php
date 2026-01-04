<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas;

use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\CreatedAtColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\TableInterface;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns\ClientIdColumn;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns\NameColumn;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns\RevokedColumn;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns\ScopesColumn;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns\UserIdColumn;

class TokenTable implements TableInterface
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            UserIdColumn::make(),
            ClientIdColumn::make(),
            NameColumn::make(),
            ScopesColumn::make(),
            RevokedColumn::make(),
            CreatedAtColumn::make(),
        ]);
    }
}
