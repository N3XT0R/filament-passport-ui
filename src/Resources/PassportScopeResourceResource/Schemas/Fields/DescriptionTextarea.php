<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas\Fields;

use Filament\Forms\Components\Textarea;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\DescriptionTextarea as BaseDescriptionTextarea;

class DescriptionTextarea extends BaseDescriptionTextarea
{
    public static function make(string $name = 'description'): Textarea
    {
        return parent::make($name)
            ->required()
            ->maxLength(255)
            ->columnSpanFull();
    }
}
