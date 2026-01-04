<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas;

use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\DescriptionColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\IdColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\IsActiveColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\NameColumn;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\TableInterface;

class PassportScopeResourceTable implements TableInterface
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            IdColumn::make(),
            NameColumn::make(),
            DescriptionColumn::make(),
            IsActiveColumn::make(),
        ]);
    }

}
