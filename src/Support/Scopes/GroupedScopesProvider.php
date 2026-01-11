<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Scopes\ScopeDTO;
use N3XT0R\LaravelPassportAuthorizationCore\Services\Scopes\ScopeRegistryService;

final readonly class GroupedScopesProvider
{
    public function __construct(
        private ScopeRegistryService $scopeRegistryService,
    ) {
    }

    /**
     * Get all scopes grouped by resource.
     * @return Collection<string, Collection<ScopeDTO>>
     */
    public function get(): Collection
    {
        return $this->scopeRegistryService
            ->allScopeNames()
            ->groupBy(fn(ScopeDTO $dto) => $dto->resource)
            ->filter(fn(Collection $scopes) => $scopes->isNotEmpty());
    }
}
