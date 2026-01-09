<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\CreateResourceUseCase;

class CreateResource extends CreateRecord
{
    protected static string $resource = PassportScopeResourceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateResourceUseCase::class)->execute(
            data: $data,
            actor: Filament::auth()->user(),
        );
    }
}
