<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\SaveOwnershipRelationUseCase;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;
use N3XT0R\FilamentPassportUi\Traits\HasResourceFormComponents;
use UnitEnum;

class ClientResource extends Resource
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

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __(config('filament-passport-ui.navigation.client_resource.group', static::$navigationGroup));
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return config('filament-passport-ui.navigation.client_resource.icon', static::$navigationIcon);
    }

    /**
     * Build the form schema for the resource.
     * @param Schema $schema
     * @return Schema
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.column.name'))
                    ->unique('clients', 'name')
                    ->required()
                    ->maxLength(255),
                Select::make('owner')
                    ->label(__('filament-passport-ui::passport-ui.client_resource.column.owner'))
                    ->options(function (): Collection {
                        return app(GetAllOwnersRelationshipUseCase::class)->execute();
                    })
                    ->saveRelationshipsUsing(function (Client $record, array $data): void {
                        app(SaveOwnershipRelationUseCase::class)
                            ->execute(
                                client: $record,
                                ownerId: $data['owner'],
                                actor: Filament::auth()->user()
                            );
                    })
                    ->searchable()
                    ->required(),
                ...static::getResourceFormComponents(),
            ]);
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
                TextColumn::make('created_at')
                    ->label(__('filament-passport-ui::passport-ui.common.created_at'))
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label(__('filament-passport-ui::passport-ui.common.updated_at'))
                    ->dateTime(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
            'create' => Pages\CreateClient::route('/create'),
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
