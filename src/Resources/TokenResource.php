<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use Laravel\Passport\Passport;
use N3XT0R\FilamentPassportUi\Resources\TokenResource\Pages;

class TokenResource extends BaseManagementResource
{

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
