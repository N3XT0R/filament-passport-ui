<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Columns;

use Filament\Tables\Columns\TextColumn;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Common\FormatHeadlineState;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\NameColumn as BaseNameColumn;

class NameColumn extends BaseNameColumn
{
    public static function make(string $name = 'name'): TextColumn
    {
        return parent::make($name)
            ->sortable(false)
            ->formatStateUsing(fn(string $state): string => app(FormatHeadlineState::class)->execute($state));
    }
}
