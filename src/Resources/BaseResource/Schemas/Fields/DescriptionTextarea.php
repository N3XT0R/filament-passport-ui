<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields;

use Filament\Forms\Components\Textarea;

class DescriptionTextarea implements FieldInterface
{

    public static function make(string $name = 'description'): Textarea
    {
        return Textarea::make($name)
            ->label(__('filament-passport-ui::passport-ui.common.description'));
    }
}
