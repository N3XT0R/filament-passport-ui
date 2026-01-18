<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Actions\DeleteAction;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\ClientResourceTable;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\ClientWizardForm;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ClientRepository;

class ClientResource extends BaseManagementResource
{

    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;
    protected static ?string $modelLabel = 'OAuth Client';
    protected static ?string $pluralModelLabel = 'OAuth Clients';

    public static function getModelLabel(): string
    {
        return __('filament-passport-ui::passport-ui.client_resource.model_label');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament-passport-ui::passport-ui.client_resource.plural_model_label');
    }

    /**
     * Build the form schema for the resource.
     * @param Schema $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return ClientWizardForm::configure($schema);
    }

    /**
     * Build the table for the resource.
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return ClientResourceTable::configure($table)
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation(),
            ]);
    }

    /**
     * Get the model class for the resource from Passport.
     * @return string
     */
    public static function getModel(): string
    {
        return Passport::clientModel();
    }

    public static function getRelations(): array
    {
        return [
            ClientResource\RelationManagers\ClientTokensRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }

    /**
     * Get the amount of clients for the navigation badge.
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return (string)app(ClientRepository::class)->count();
    }
}
