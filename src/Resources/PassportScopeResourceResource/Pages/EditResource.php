<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Application\UseCases\Resources\EditResourceUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource;

class EditResource extends EditRecord
{
    protected static string $resource = PassportScopeResourceResource::class;

    /**
     * @param Model&PassportScopeResource $record
     * @param array $data
     * @return Model&PassportScopeResource
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(EditResourceUseCase::class)->execute(
            resource: $record,
            data: $data,
            actor: auth()->user(),
        );
    }
}
