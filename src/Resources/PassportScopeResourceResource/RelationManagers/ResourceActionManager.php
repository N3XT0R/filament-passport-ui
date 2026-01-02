<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResourceActionManager extends RelationManager
{
    protected static string $relationship = 'actions';

    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.name'
                        )
                    ),
                TextColumn::make('description')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.description'
                        )
                    ),
                IconColumn::make('is_active')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_active'
                        )
                    )
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ]);
    }
}
