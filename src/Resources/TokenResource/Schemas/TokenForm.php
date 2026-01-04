<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\TokenResource\Schemas;

use Filament\Schemas\Schema;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;

class TokenForm implements FormInterface
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            ]);
    }
}
