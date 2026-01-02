<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;

class PassportScopeResourceResource extends BaseManagementResource
{
    protected static ?string $model = PassportScopeResource::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static string|\UnitEnum|null $navigationGroup = 'filament-passport-ui::passport-ui.navigation.group';


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResources::route('/'),
        ];
    }
}
