<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Actions;

use Filament\Actions\DeleteAction as FilamentDeleteAction;
use Filament\Facades\Filament;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Actions\ActionInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\DeleteActionUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;

class DeleteAction implements ActionInterface
{

    public static function make(string $name = 'delete'): FilamentDeleteAction
    {
        return FilamentDeleteAction::make($name)
            ->requiresConfirmation()
            ->action(function (PassportScopeAction $record): bool {
                return app(DeleteActionUseCase::class)->execute($record, Filament::auth()->user());
            });
    }
}
