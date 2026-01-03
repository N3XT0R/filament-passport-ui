<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Application\UseCases\Client\CreateClientUseCase;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateClientUseCase::class)->execute(
            data: $data,
            actor: auth()->user(),
        );
    }
}
