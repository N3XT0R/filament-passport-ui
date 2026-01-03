<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Pages;

class TokenResource extends BaseManagementResource
{

    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedLockClosed;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ]);
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
