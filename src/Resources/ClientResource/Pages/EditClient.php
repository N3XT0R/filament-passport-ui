<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Application\UseCases\Client\EditClientUseCase;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof Client) {
            throw new \RuntimeException('Record is not an instance of Client model.');
        }

        return app(EditClientUseCase::class)->execute(
            client: $record,
            data: $data,
            actor: Filament::auth()->user(),
        );
    }
}
