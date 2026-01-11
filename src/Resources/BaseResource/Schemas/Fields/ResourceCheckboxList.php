<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Field;
use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Scopes\ScopeDTO;

class ResourceCheckboxList
{
    public static function make(
        string $resource,
        Collection $scopes,
        Collection $granted,
        Collection $disabled,
        string $statePath = 'scopes'
    ): Field {
        $selectableScopes = $scopes->reject(
            fn(ScopeDTO $dto) => $disabled->contains($dto->scope)
        );

        return CheckboxList::make("scopes_$resource")
            ->statePath("$statePath.$resource")
            ->label(ucfirst($resource))
            ->options(
                $selectableScopes
                    ->mapWithKeys(fn(ScopeDTO $dto) => [$dto->scope => $dto->scope])
                    ->all()
            )
            ->descriptions(
                $selectableScopes
                    ->mapWithKeys(fn(ScopeDTO $dto) => [$dto->scope => $dto->description])
                    ->filter()
                    ->all()
            )
            ->default(
                $granted
                    ->intersect($selectableScopes->pluck('scope'))
                    ->values()
                    ->all()
            )
            ->afterStateHydrated(function (CheckboxList $component) use ($granted, $selectableScopes) {
                $allowed = $selectableScopes->pluck('scope');

                $component->state(
                    $granted
                        ->intersect($allowed)
                        ->values()
                        ->all()
                );
            })
            ->columns(3)
            ->bulkToggleable();
    }

}