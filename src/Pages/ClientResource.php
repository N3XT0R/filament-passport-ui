<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Pages;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
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
                TextColumn::make('name')
                    ->weight(FontWeight::Medium)
                    ->label(__('filament-passport-ui:filament-passport-ui.client_resource.column.name'))
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->searchable(),
            ]);
    }

    public static function getModel(): string
    {
        return Passport::clientModel();
    }
}
