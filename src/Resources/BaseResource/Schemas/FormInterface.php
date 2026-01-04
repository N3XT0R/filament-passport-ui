<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas;

use Filament\Schemas\Schema;

interface FormInterface
{
    /**
     * Configure the given form schema.
     * @param Schema $schema
     * @return Schema
     */
    public static function configure(Schema $schema): Schema;
}
