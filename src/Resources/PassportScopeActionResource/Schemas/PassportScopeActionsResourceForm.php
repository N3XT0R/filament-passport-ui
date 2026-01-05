<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\DescriptionTextarea;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\IsActiveCheckbox;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\FormInterface;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\Fields\NameInput;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Schemas\Fields\ResourceIdSelect;

class PassportScopeActionsResourceForm implements FormInterface
{

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()
                ->schema([
                    NameInput::make(),
                    ResourceIdSelect::make(),
                ])
                ->columnSpanFull(),
            DescriptionTextarea::make()
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            IsActiveCheckbox::make(),
        ]);
    }
}
