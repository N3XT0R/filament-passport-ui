<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\RelationManagers;

class PassportScopeResourceResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeResource::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.form.name'))
                ->unique('passport_scope_resources', 'name')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Textarea::make('description')
                ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.form.description'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Checkbox::make('is_active')
                ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.form.is_active'))
                ->default(true),
        ]);
    }

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
                IconColumn::make('is_active')
                    ->label(__('filament-passport-ui::passport-ui.passport_scope_resource_resource.column.is_active'))
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
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
