<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas;

use Filament\Schemas\Schema;

interface FormInterface
{
    public static function configure(Schema $schema): Schema;
}
