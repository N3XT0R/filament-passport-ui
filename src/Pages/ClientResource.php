<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Filament\Tables\Columns\TextColumn;

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
                    ->maxLength(255)
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
                TextColumn::make('id')
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.column.id'))
                    ->searchable(),
                TextColumn::make('secret')
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.column.secret'))
                    ->searchable(),
            ]);
    }

    public static function getModel(): string
    {
        return Passport::clientModel();
    }
}
