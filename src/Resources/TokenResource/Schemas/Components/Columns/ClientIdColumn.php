<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns;

use Filament\Tables\Columns\TextColumn;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Token\FormatClientIdState;

class ClientIdColumn
{
    public static function make(string $name = 'client_id'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.token_resource.column.client'))
            ->formatStateUsing(function (string $state): ?string {
                return app(FormatClientIdState::class)->execute($state);
            })
            ->searchable();
    }
}
