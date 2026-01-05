<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas;

use Filament\Schemas\Schema;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\IsActiveCheckbox;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas\Fields\DescriptionTextarea;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Schemas\Fields\NameInput;

class PassportScopeResourceForm implements FormInterface
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                NameInput::make(),
                DescriptionTextarea::make(),
                IsActiveCheckbox::make(),
            ]);
    }
}
