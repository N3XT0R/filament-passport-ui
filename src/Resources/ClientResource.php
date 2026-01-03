<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Carbon\CarbonInterface;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Application\UseCases\Grant\GetAllowedGrantTypeOptions;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;
use N3XT0R\FilamentPassportUi\Traits\HasResourceFormComponents;

class ClientResource extends BaseManagementResource
{
    use HasResourceFormComponents;

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
        $components = [
            Hidden::make('id')
                ->unique('oauth_clients', 'id'),
            TextInput::make('name')
                ->label(__('filament-passport-ui::passport-ui.client_resource.column.name'))
                ->required()
                ->maxLength(255),
            Select::make('owner')
                ->label(__('filament-passport-ui::passport-ui.client_resource.column.owner'))
                ->placeholder(__('filament-passport-ui::passport-ui.common.none'))
                ->options(app(GetAllOwnersRelationshipUseCase::class)->execute())
                ->formatStateUsing(function (?string $state, ?Client $record): string|int|null {
                    if ($record === null) {
                        return $state;
                    }

                    return $record->owner?->getKey();
                })
                ->default(null)
                ->nullable()
                ->helperText(__('filament-passport-ui::passport-ui.client_resource.form.owner_hint')),
            Select::make('grant_type')
                ->label(__('filament-passport-ui::passport-ui.client_resource.column.grant_type'))
                ->options(app(GetAllowedGrantTypeOptions::class)->execute())
                ->formatStateUsing(function (?string $state, ?Client $record): ?string {
                    if ($record === null) {
                        return $state;
                    }

                    $grantTypes = (array)$record->getAttribute('grant_types');

                    return current($grantTypes);
                })
                ->disabled(fn(?Client $record): bool => $record !== null)
                ->preload()
                ->required(),
        ];

        /**
         * merge getResourceFormComponents if enabled
         */
        if (static::isResourceFormComponentsEnabled()) {
            $components = array_merge($components, static::getResourceFormComponents($schema->getRecord()));
        }

        return $schema
            ->components($components);
    }

    /**
     * Build the table for the resource.
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.column.name'))
                    ->formatStateUsing(fn(string $state): string => Str::headline($state))
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.column.owner'))
                    ->searchable(),
                TextColumn::make('grant_types')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.column.grant_type'))
                    ->listWithLineBreaks()
                    ->searchable(),
                TextColumn::make('last_login')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.column.last_login'))
                    ->dateTime()
                    ->getStateUsing(function (Client $record): ?CarbonInterface {
                        return app(ClientRepository::class)->getLastLoginAtForClient($record);
                    }),
                TextColumn::make('created_at')
                    ->label(__('filament-passport-ui::passport-ui.common.created_at'))
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label(__('filament-passport-ui::passport-ui.common.updated_at'))
                    ->dateTime(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
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
