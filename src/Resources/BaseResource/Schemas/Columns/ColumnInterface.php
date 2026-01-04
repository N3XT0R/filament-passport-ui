<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns;

use Filament\Tables\Columns\Column;

interface ColumnInterface
{
    public static function make(string $name = ''): Column;
}
