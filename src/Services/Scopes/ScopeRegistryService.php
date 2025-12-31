<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services\Scopes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use N3XT0R\FilamentPassportUi\DTO\Scopes\ScopeDTO;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\ValueObjects\Scopes\ScopeName;

readonly class ScopeRegistryService
{
    public function __construct(
        private ResourceRepository $resourceRepository,
        private ActionRepository $actionRepository
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
        return Schema::hasTable('passport_scope_resources')
            && Schema::hasTable('passport_scope_actions');
    }

    private function actionsForResource(PassportScopeResource $resource, Collection $actions): Collection
    {
        return $actions->filter(
            fn ($action) => $action->resource_id === null
                || $action->resource_id === $resource->getKey()
        );
    }

}
