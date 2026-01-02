<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource;

class ListActions extends ListRecords
{
    protected static string $resource = PassportScopeActionResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
