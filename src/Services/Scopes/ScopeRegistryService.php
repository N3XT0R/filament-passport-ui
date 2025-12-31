<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\BaseCachedRepositoryDecorator;
use N3XT0R\FilamentPassportUi\ValueObjects\Scopes\ScopeName;

readonly class ScopeRegistryService
{
    public function __construct(
        private ResourceRepositoryContract $resourceRepository,
        private ActionRepositoryContract $actionRepository
    ) {
    }

    /**
     * Get all active scopes in the system.
     * @return Collection<string, string>
     */
    public function all(): Collection
    {
        $resources = $this->resourceRepository->active();
        $actions = $this->actionRepository->active();

        $scopes = collect();

        foreach ($resources as $resource) {
            foreach ($this->actionsForResource($resource, $actions) as $action) {
                $scopeName = ScopeName::from($resource, $action);

                $scopes->put(
                    $scopeName->value(),
                    ''
                );
            }
        }

        return $scopes;
    }

    /**
     * Get all active scope names with descriptions.
     * @return Collection<ScopeDTO>
     */
    public function allScopeNames(): Collection
    {
        $resources = $this->resourceRepository->active();
        $actions = $this->actionRepository->active();

        $scopeNames = collect();

        foreach ($resources as $resource) {
            foreach ($this->actionsForResource($resource, $actions) as $action) {
                $scopeName = ScopeName::from($resource, $action);
                $scopeNames->push(
                    new ScopeDTO(
                        scope: $scopeName->value(),
                        isGlobal: $action->resource_id === null,
                        resource: $resource->getAttribute('name'),
                        description: $action->getAttribute('description')
                    )
                );
            }
        }

        return $scopeNames;
    }

    public function isMigrated(): bool
    {
        return $this->resourceRepository->isMigrated()
            && $this->actionRepository->isMigrated();
    }

    /**
     * Filter actions for a given resource (either global or specific to the resource).
     * @param PassportScopeResource $resource
     * @param Collection $actions
     * @return Collection
     */
    private function actionsForResource(PassportScopeResource $resource, Collection $actions): Collection
    {
        return $actions->filter(
            fn ($action) => $action->resource_id === null
                || $action->resource_id === $resource->getKey()
        );
    }

    /**
     * Clear the caches of the underlying repositories.
     * @return void
     */
    public function clearCache(): void
    {
        if ($this->actionRepository instanceof BaseCachedRepositoryDecorator) {
            $this->actionRepository->clearCache();
        }

        if ($this->resourceRepository instanceof BaseCachedRepositoryDecorator) {
            $this->resourceRepository->clearCache();
        }
    }
}
