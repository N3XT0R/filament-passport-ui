<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Resources\TokenResource;

class ClientTokensRelationManager extends RelationManager
{
    protected static string $relationship = 'tokens';

    protected static ?string $recordTitleAttribute = 'name';


    public function table(Table $table): Table
    {
        return TokenResource::table($table);
    }
}
