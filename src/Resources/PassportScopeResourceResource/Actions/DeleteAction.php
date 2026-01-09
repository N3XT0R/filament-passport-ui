<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource\Actions;

use Filament\Actions\DeleteAction as FilamentDeleteAction;
use Filament\Facades\Filament;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Actions\ActionInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\DeleteResourceUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

class DeleteAction implements ActionInterface
{

    public static function make(string $name = 'delete'): FilamentDeleteAction
    {
        return FilamentDeleteAction::make($name)
            ->requiresConfirmation()
            ->action(function (PassportScopeResource $record): bool {
                return app(DeleteResourceUseCase::class)->execute($record, Filament::auth()->user());
            });
    }
}
