<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;

class PassportScopeActionsResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeAction::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(
                                __(
                                    'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.name'
                                )
                            )
                            ->unique(
                                'passport_scope_actions',
                                'name',
                            )
                            ->required()
                            ->maxLength(255),
                        Select::make('resource_id')
                            ->label(
                                __(
                                    'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.resource_id'
                                )
                            )
                            ->relationship(
                                name: 'resource',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn($query) => $query->where(
                                    'is_active',
                                    true
                                )
                            )
                            ->default(null)
                            ->nullable()
                            ->helperText(
                                __(
                                    'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.resource_id_helper_text'
                                )
                            ),
                    ])
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.description'
                        )
                    )
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Checkbox::make('is_active')
                    ->label(
                        __(
                            'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.is_active'
                        )
                    )
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_actions_resource.column.id'))
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_actions_resource.column.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_actions_resource.column.description'))
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_active'))
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_global')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_actions_resource.column.is_global'))
                    ->boolean()
                    ->sortable()
                    ->state(fn(PassportScopeAction $record): bool => $record->resource_id === null),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }

    /**
     * Get the amount of clients for the navigation badge.
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return (string)app(ActionRepository::class)->count();
    }
}
