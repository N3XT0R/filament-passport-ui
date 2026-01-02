<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use N3XT0R\FilamentPassportUi\Application\UseCases\Owners\SaveOwnershipRelationUseCase;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        dd($data);
        app(SaveOwnershipRelationUseCase::class)
            ->execute(
                client: $data['id'],
                ownerId: $data['owner'],
                actor: Filament::auth()->user()
            );

        return parent::mutateFormDataBeforeSave($data);
    }
}
