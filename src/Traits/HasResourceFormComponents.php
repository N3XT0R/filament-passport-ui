<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Traits;

use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

trait HasResourceFormComponents
{
    public static function getResourceFormComponentsStatic(): array
    {
        return new static()->getResourceFormComponents();
    }

    public function getResourceFormComponents(): array
    {
        return [
            Section::make(__('filament-passport-ui:filament-passport-ui.common.scopes'))
                ->schema([
                    Grid::make()
                        ->schema($this->buildScopeSections()),
                ])
                ->collapsible(),
        ];
    }

    protected function buildScopeSections(): array
    {
        $scopes = app(ScopeRegistryService::class)->allScopeNames();

        return $scopes
            ->groupBy(fn(ScopeDTO $dto) => $dto->resource)
            ->map(fn(Collection $group, string $resource) => $this->buildScopeSection($resource, $group)
            )
            ->values()
            ->all();
    }

    protected function buildScopeSection(string $resource, Collection $scopes): Section
    {
        return Section::make($resource)
            ->schema([
                CheckboxList::make('scopes')
                    ->options(
                        $scopes->mapWithKeys(
                            fn(ScopeDTO $dto) => [
                                $dto->scope => $dto->scope,
                            ]
                        )
                    )
                    ->descriptions(
                        $scopes->mapWithKeys(
                            fn(ScopeDTO $dto) => [
                                $dto->scope => $dto->description,
                            ]
                        )->filter()
                    )
                    ->columns(3)
                    ->bulkToggleable(),
            ])
            ->collapsible();
    }
}
