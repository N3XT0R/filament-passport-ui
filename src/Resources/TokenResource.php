<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Pages;
use N3XT0R\FilamentPassportUi\Services\ClientService;

class TokenResource extends BaseManagementResource
{

    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedLockClosed;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.user_name'))
                    ->formatStateUsing(function (Model $record): string {
                        return (string)app(ClientService::class)->getOwnerLabelAttribute(
                            $record->getAttribute('client_id') ?? ''
                        );
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('client_id')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.client'))
                    ->formatStateUsing(function (string $state): string {
                        return app(ClientRepository::class)->find($state)?->name;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.name'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('scopes')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.scopes'))
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                IconColumn::make('revoked')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.revoked'))
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.created_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label(__('filament-passport-ui::passport-ui.token_resource.column.expires_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }


    /**
     * Get the model class for the token from Passport.
     * @return string
     */
    public static function getModel(): string
    {
        return Passport::tokenModel();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTokens::route('/'),
        ];
    }
}
