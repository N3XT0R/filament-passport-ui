<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

    public function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }
}
