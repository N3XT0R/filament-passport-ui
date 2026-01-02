<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;

class PassportScopeActionResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeAction::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActions::route('/'),
            'create' => Pages\CreateAction::route('/create'),
            'edit' => Pages\EditAction::route('/{record}/edit'),
        ];
    }

    /**
     * Get the amount of clients for the navigation badge.
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return (string)app(ActionRepository::class)->count();
    }
}
