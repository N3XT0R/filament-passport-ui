<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Builder;

use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Services\GrantService;
use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

readonly class ScopeFormSectionBuilder
{
    public function __construct(
        private ScopeRegistryService $scopeRegistryService,
        private GrantService $grantService,
    ) {
    }

    /**
     * Build form sections for scopes grouped by resource.
     * @param HasPassportScopeGrantsInterface|null $record
     * @return array<int, Section>
     */
    public function buildSections(?HasPassportScopeGrantsInterface $record = null): array
    {
        return $this->scopeRegistryService
            ->allScopeNames()
            ->groupBy(fn(ScopeDTO $dto) => $dto->resource)
            ->filter(fn(Collection $scopes) => $scopes->isNotEmpty())
            ->map(
                fn(Collection $scopes, string $resource) => $this->buildSection($resource, $scopes, $record)
            )
            ->values()
            ->all();
    }

    protected function buildSection(
        string $resource,
        Collection $scopes,
        ?HasPassportScopeGrantsInterface $record = null
    ): Section {
        $grantedScopes = $this->getGrantedScopesForRecord($record);

        return Section::make($resource)
            ->schema([
                CheckboxList::make($resource)
                    ->statePath("scopes.$resource")
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
                    ->bulkToggleable()
                    ->afterStateHydrated(function (CheckboxList $component) use ($grantedScopes) {
                        $component->state(
                            $grantedScopes->map(fn(string $scope) => $scope)->all()
                        );
                    }),
            ])
            ->columnSpanFull()
            ->collapsible();
    }


    private function getGrantedScopesForRecord(?HasPassportScopeGrantsInterface $record): Collection
    {
        $collection = new Collection();
        if ($record === null) {
            return $collection;
        }

        $owner = $record->getAttribute('owner');
        if ($owner === null) {
            return $this->grantService->getTokenableGrantsAsScopes($record);
        }

        return $this->grantService->getTokenableGrantsAsScopes($owner);
    }
}
