<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\Columns;

use Filament\Tables\Columns\IconColumn;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\ScopeAction\FormatIsGlobalState;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Columns\ColumnInterface;

class IsGlobalColumn implements ColumnInterface
{

    public static function make(string $name = 'is_global'): IconColumn
    {
        return IconColumn::make($name)
            ->label(__('filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_global'))
            ->boolean()
            ->sortable()
            ->state(fn(PassportScopeAction $record): bool => app(FormatIsGlobalState::class)->execute($record));
    }
}
