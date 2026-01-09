<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Actions\DeleteAction;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas\PassportScopeResourceForm;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas\PassportScopeResourceTable;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

class PassportScopeResourceResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeResource::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    public static function form(Schema $schema): Schema
    {
        return PassportScopeResourceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PassportScopeResourceTable::configure($table)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ResourceActionManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResources::route('/'),
            'create' => Pages\CreateResource::route('/create'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }

    /**
     * Get the amount of clients for the navigation badge.
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return (string)app(ResourceRepository::class)->count();
    }
}
