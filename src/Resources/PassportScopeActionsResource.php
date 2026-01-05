<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\DescriptionTextarea;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\IsActiveCheckbox;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\PassportScopeActionsResourceTable;

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
                            ->placeholder(__('filament-passport-ui::passport-ui.common.none'))
                            ->options(app(ResourceRepository::class)->active()->pluck('name', 'id'))
                            ->default(null)
                            ->nullable()
                            ->helperText(
                                __(
                                    'filament-passport-ui::passport-ui.passport_scope_actions_resource.form.resource_id_helper_text'
                                )
                            ),
                    ])
                    ->columnSpanFull(),
                DescriptionTextarea::make()
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                IsActiveCheckbox::make(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return PassportScopeActionsResourceTable::configure($table)
            ->recordActions([
                EditAction::make('edit'),
                DeleteAction::make('delete')
                    ->requiresConfirmation(),
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
