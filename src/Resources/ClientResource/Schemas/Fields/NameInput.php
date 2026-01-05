<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields;

use Filament\Forms\Components\TextInput;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\NameInput as BaseNameInput;

class NameInput extends BaseNameInput
{
    public static function make(string $name = 'name'): TextInput
    {
        return parent::make($name)
            ->required()
            ->maxLength(255);
    }
}
