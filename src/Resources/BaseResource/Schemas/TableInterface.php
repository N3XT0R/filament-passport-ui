<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas;

use Filament\Tables\Table;

interface TableInterface
{
    public static function configure(Table $table): Table;
}
