<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas;

use Filament\Schemas\Schema;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\GrantTypeSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\IdHidden;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\NameInput;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\OwnerSelect;
use N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields\SecretInput;

class ClientResourceForm implements FormInterface
{

    public static function configure(Schema $schema, array $additionalComponents = []): Schema
    {
        $components = [
            IdHidden::make(),
            NameInput::make(),
            OwnerSelect::make(),
            GrantTypeSelect::make(),
            SecretInput::make(),
        ];

        return $schema->components(
            array_merge(
                $components,
                $additionalComponents
            )
        );
    }
}
