<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\BaseResource\Components;

use Filament\Schemas\Components\Section;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Resources\BaseResource\Schemas\Fields\ResourceCheckboxList;
use N3XT0R\FilamentPassportUi\Support\Scopes\GrantedScopesByResourceProvider;
use N3XT0R\FilamentPassportUi\Support\Scopes\GroupedScopesProvider;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;

class ScopeCheckboxList
{
    public function __construct(
        protected GroupedScopesProvider $groupedScopesProvider,
        protected GrantedScopesByResourceProvider $grantedScopesByResourceProvider,
    ) {
    }


    /**
     * Create and configure the scope checkbox list section.
     * @param string $context
     * @param string $name
     * @param HasPassportScopeGrantsInterface|null $record
     * @param string $statePath
     * @param array|Collection|null $allowed
     * @return Section
     */
    public static function make(
        string $context,
        string $name,
        ?HasPassportScopeGrantsInterface $record = null,
        string $statePath = 'scopes',
        array|Collection|null $allowed = null,
    ): Section {
        return app(static::class)->configure(
            context: $context,
            name: $name,
            record: $record,
            statePath: $statePath,
            allowed: collect($allowed ?? [])
        );
    }

    /**
     * Configure the scope checkbox list section.
     * @param string $context
     * @param string $name
     * @param HasPassportScopeGrantsInterface|null $record
     * @param string $statePath
     * @param Collection|null $allowed
     * @return Section
     */
    public function configure(
        string $context,
        string $name,
        ?HasPassportScopeGrantsInterface $record,
        string $statePath = 'scopes',
        ?Collection $allowed = null,
    ): Section {
        return Section::make($name)
            ->heading(__('filament-passport-ui::passport-ui.common.scopes'))
            ->schema(
                $this->buildSections(
                    context: $context,
                    record: $record,
                    statePath: $statePath,
                    allowedByResource: $allowed
                )
            )
            ->columnSpanFull()
            ->collapsible();
    }


    /**
     * Build form sections for scopes grouped by resource.
     * @param string $context
     * @param HasPassportScopeGrantsInterface|null $record
     * @param string $statePath
     * @param Collection|null $allowedByResource
     * @return array
     */
    private function buildSections(
        string $context,
        ?HasPassportScopeGrantsInterface $record,
        string $statePath = 'scopes',
        ?Collection $allowedByResource = null,
    ): array {
        $allowedByResource ??= collect();
        $groups = $this->groupedScopesProvider->get();
        $grantedByResource = $this->grantedScopesByResourceProvider->get($record);


        return $groups->map(function (
            Collection $scopes,
            string $resource
        )
        use ($context, $grantedByResource, $statePath, $allowedByResource) {
            return ResourceCheckboxList::make(
                resource: $resource,
                scopes: $scopes,
                granted: $grantedByResource->get($resource, collect()),
                allowed: $this->groupAllowedByResource($allowedByResource),
                statePath: $statePath,
                context: $context,
            );
        })->values()->all();
    }

    /**
     * Group allowed scopes by resource.
     * @param Collection $allowed
     * @return Collection
     */
    private function groupAllowedByResource(Collection $allowed): Collection
    {
        return $allowed
            ->filter(fn(string $scope) => str_contains($scope, ':'))
            ->groupBy(fn(string $scope) => explode(':', $scope, 2)[0])
            ->map(fn(Collection $items) => $items->values());
    }

}