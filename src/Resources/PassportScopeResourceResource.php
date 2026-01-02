<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;

class PassportScopeResourceResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeResource::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.column.id'))
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.column.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.column.description'))
                    ->sortable()
                    ->searchable(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResources::route('/'),
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
