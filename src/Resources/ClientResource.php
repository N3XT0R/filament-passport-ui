<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\SaveOwnershipRelationUseCase;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

class ClientResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.column.name'))
                    ->unique('clients', 'name')
                    ->required()
                    ->maxLength(255),
                Select::make('owner')
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.field.owner'))
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.column.name'))
                    ->formatStateUsing(fn(string $state): string => Str::headline($state))
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.column.owner'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('filament-passport-ui:filament-passport-ui.common.created_at'))
                    ->dateTime(),
                TextColumn::make('updated_at')
                    ->label(__('filament-passport-ui:filament-passport-ui.common.updated_at'))
                    ->dateTime(),
            ])
            ->recordActions([
                EditAction::class,
                DeleteAction::class,
            ])
            ->toolbarActions([
                DeleteBulkAction::class,
            ]);
    }

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
}
