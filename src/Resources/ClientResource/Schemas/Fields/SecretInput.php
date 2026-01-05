<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields;

use Filament\Forms\Components\TextInput;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\FieldInterface;

class SecretInput implements FieldInterface
{

    public static function make(string $name = 'secret'): TextInput
    {
        return TextInput::make('secret')
            ->label(__('filament-passport-ui::passport-ui.client_resource.form.secret_label'))
            ->helperText(
                __('filament-passport-ui::passport-ui.client_resource.form.secret_description')
            )
            ->disabled()
            ->copyable();
    }
}
