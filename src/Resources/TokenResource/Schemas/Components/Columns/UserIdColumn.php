<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas\Components\Columns;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Token\FormatUserIdState;

class UserIdColumn
{
    public static function make(string $name = 'user_id'): TextColumn
    {
        return TextColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.token_resource.column.user_name'))
            ->formatStateUsing(function (Model $record): ?string {
                return app(FormatUserIdState::class)->execute($record);
            })
            ->searchable()
            ->toggleable();
    }
}
