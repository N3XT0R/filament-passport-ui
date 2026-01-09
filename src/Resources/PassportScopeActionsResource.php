<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Actions\DeleteAction;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\PassportScopeActionsResourceForm;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\PassportScopeActionsResourceTable;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ActionRepository;

class PassportScopeActionsResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeAction::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;


    public static function form(Schema $schema): Schema
    {
        return PassportScopeActionsResourceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PassportScopeActionsResourceTable::configure($table)
            ->recordActions([
                EditAction::make('edit'),
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
