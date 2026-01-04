<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas;

use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\TableInterface;

class TokenTable implements TableInterface
{
    public static function configure(Table $table): Table
    {
        return $table->columns([

        ]);
    }
}
