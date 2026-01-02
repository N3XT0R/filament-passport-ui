<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;

class ResourceActionManager extends RelationManager
{
    protected static string $relationship = 'actions';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.name'
                        )
                    )
                    ->unique(
                        'passport_scope_actions',
                        'name',
                        ignoreRecord: true,
                        modifyRuleUsing: fn(Builder $query) => $query->whereNull('resource_id')
                    )
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        $table = PassportScopeActionsResource::table($table);
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->orWhere('resource_id', null))
            ->headerActions([
                CreateAction::make()
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.header_action.create'
                        )
                    ),
            ]);
    }
}
