<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Builder;

use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

readonly class ScopeFormSectionBuilder
{
    public function __construct(
        private ScopeRegistryService $scopeRegistryService
    ) {
    }

    /**
     * @return array<int, Section>
     */
    public function buildSections(): array
    {
        return $this->scopeRegistryService
            ->allScopeNames()
            ->groupBy(fn (ScopeDTO $dto) => $dto->resource)
            ->filter(fn (Collection $scopes) => $scopes->isNotEmpty())
            ->map(
                fn (Collection $scopes, string $resource) => $this->buildSection($resource, $scopes)
            )
            ->values()
            ->all();
    }

    protected function buildSection(string $resource, Collection $scopes): Section
    {
        return Section::make($resource)
            ->schema([
                CheckboxList::make('scopes')
                    ->options(
                        $scopes->mapWithKeys(
                            fn (ScopeDTO $dto) => [
                                $dto->scope => $dto->scope,
                            ]
                        )
                    )
                    ->descriptions(
                        $scopes->mapWithKeys(
                            fn (ScopeDTO $dto) => [
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
