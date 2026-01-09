<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Actions;

use Filament\Actions\DeleteAction as FilamentDeleteAction;
use Filament\Facades\Filament;
use N3XT0R\FilamentPassportUi\Application\UseCases\Client\DeleteClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Actions\ActionInterface;

class DeleteAction implements ActionInterface
{

    public static function make(string $name = 'delete'): FilamentDeleteAction
    {
        return FilamentDeleteAction::make($name)
            ->requiresConfirmation()
            ->action(function (Client $record): bool {
                return app(DeleteClientUseCase::class)->execute($record, Filament::auth()->user());
            });
    }
}
