<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields;

use Filament\Forms\Components\Toggle;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\FieldInterface;

class RevokeToggle implements FieldInterface
{

    public static function make(string $name = 'revoked'): Toggle
    {
        return Toggle::make($name)
            ->label(__('filament-passport-ui::passport-ui.client_resource.form.revoke_label'));
    }
}
