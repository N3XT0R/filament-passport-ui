<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);

        return [];
    }


    protected function afterCreate(): void
    {
    }
}
