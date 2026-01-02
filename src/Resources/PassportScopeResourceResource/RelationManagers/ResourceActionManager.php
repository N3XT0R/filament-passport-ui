<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;

class ResourceActionManager extends RelationManager
{
    protected static string $relationship = 'actions';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orWhere('resource_id', null))
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.name'
                        )
                    )
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.description'
                        )
                    )
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_active'
                        )
                    )
                    ->boolean()
                    ->sortable()
                    ->action(
                        fn(PassportScopeAction $record) => $record->setAttribute(
                            'is_active',
                            !$record->is_active
                        )->save(),
                    )
                    ->tooltip(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_active_tooltip'
                        )
                    ),
                IconColumn::make('is_global')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_global'
                        )
                    )
                    ->getStateUsing(fn(PassportScopeAction $record) => $record->resource_id === null)
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.header_action.create'
                        )
                    ),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn(PassportScopeAction $record) => $record->resource_id !== null),
                DeleteAction::make()
                    ->visible(fn(PassportScopeAction $record) => $record->resource_id !== null),
            ]);
    }
}
