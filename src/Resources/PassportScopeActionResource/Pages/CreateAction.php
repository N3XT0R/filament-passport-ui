<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Application\UseCases\Actions\CreateActionUseCase;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;

class CreateAction extends CreateRecord
{
    protected static string $resource = PassportScopeActionsResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateActionUseCase::class)->execute(
            data: $data,
            actor: Filament::auth()->user(),
        );
    }
}
