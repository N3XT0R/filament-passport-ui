<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields;

use Filament\Forms\Components\Select;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Client\FormatOwnerState;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\FieldInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

class OwnerSelect implements FieldInterface
{

    public static function make(string $name = 'owner'): Select
    {
        return Select::make($name)
            ->label(__('filament-passport-ui::passport-ui.client_resource.column.owner'))
            ->placeholder(__('filament-passport-ui::passport-ui.common.none'))
            ->options(app(GetAllOwnersRelationshipUseCase::class)->execute())
            ->formatStateUsing(function (?string $state, ?Client $record): string|int|null {
                return app(FormatOwnerState::class)->execute($state, $record);
            })
            ->default(null)
            ->nullable()
            ->helperText(__('filament-passport-ui::passport-ui.client_resource.form.owner_hint'));
    }
}
