<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields;

use Filament\Forms\Components\TextInput;

class NameInput implements FieldInterface
{

    public static function make(string $name = 'name'): TextInput
    {
        return TextInput::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.name'));
    }
}
