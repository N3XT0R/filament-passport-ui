<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\ClientResource\Schemas\Fields;

use Filament\Forms\Components\Select;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Client\FormatClientGrantTypeState;
use N3XT0R\FilamentPassportUi\Application\UseCases\Grant\GetAllowedGrantTypeOptions;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\FieldInterface;

class GrantTypeSelect implements FieldInterface
{

    public static function make(string $name = 'grant_type'): Select
    {
        return Select::make($name)
            ->label(__('filament-passport-ui::passport-ui.client_resource.column.grant_type'))
            ->options(app(GetAllowedGrantTypeOptions::class)->execute())
            ->formatStateUsing(function (?string $state, ?Client $record): ?string {
                return app(FormatClientGrantTypeState::class)->execute($state, $record);
            })
            ->disabled(fn(?Client $record): bool => $record !== null)
            ->preload()
            ->required();
    }
}
