<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Filament\Tables\Columns\TextColumn;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\SaveOwnershipRelationUseCase;

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
                            ->execute($record, $data['owner'], Filament::auth()->user());
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
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
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
            ]);
    }

    public static function getModel(): string
    {
        return Passport::clientModel();
    }
}
