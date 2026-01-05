<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas\Fields;

use Filament\Forms\Components\TextInput;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\NameInput as BaseInput;

class NameInput extends BaseInput
{
    public static function make(string $name = 'name'): TextInput
    {
        return parent::make($name)
            ->unique('passport_scope_resources', 'name')
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }
}
