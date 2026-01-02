<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource;

class ListResources extends ListRecords
{
    protected static string $resource = PassportScopeResourceResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
