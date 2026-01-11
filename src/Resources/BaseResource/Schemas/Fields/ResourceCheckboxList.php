<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields;

use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Scopes\ScopeDTO;

class ResourceCheckboxList
{
    /**
     * Create a checkbox list for a given resource's scopes.
     * @param string $resource
     * @param Collection<ScopeDTO> $scopes
     * @param Collection<string>|null $granted
     * @param Collection<string>|null $allowed
     * @param string $statePath
     * @param string $context
     * @return CheckboxList
     */
    public static function make(
        string $resource,
        Collection $scopes,
        ?Collection $granted = null,
        ?Collection $allowed = null,
        string $statePath = 'scopes',
        string $context = '',
    ): CheckboxList {
        $granted ??= collect();
        // By default, all scopes are selectable
        $selectableScopes = $scopes;

        if ($allowed !== null && $allowed->isNotEmpty()) {
            $allowedFlat = $allowed->flatten();
            $selectableScopes = $scopes->filter(
                fn(ScopeDTO $dto) => $allowedFlat->contains($dto->scope)
            );
        }

        $checkboxListName = $context !== '' ? "{$context}_scopes_$resource" : "scopes_$resource";

        return CheckboxList::make($checkboxListName)
            ->hidden($selectableScopes->isEmpty())
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