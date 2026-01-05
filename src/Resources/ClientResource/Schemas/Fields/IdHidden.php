<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields;

use Filament\Forms\Components\Hidden;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\FieldInterface;

class IdHidden implements FieldInterface
{

    public static function make(string $name = 'id'): Hidden
    {
        return Hidden::make($name)
            ->unique('oauth_clients', 'id');
    }
}
