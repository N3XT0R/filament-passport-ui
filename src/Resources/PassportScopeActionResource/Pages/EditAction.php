<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\EditActionUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;

class EditAction extends EditRecord
{
    protected static string $resource = PassportScopeActionsResource::class;

    /**
     * @param Model&PassportScopeAction $record
     * @param array $data
     * @return Model&PassportScopeAction
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(EditActionUseCase::class)->execute(
            action: $record,
            data: $data,
            actor: Filament::auth()->user(),
        );
    }
}
