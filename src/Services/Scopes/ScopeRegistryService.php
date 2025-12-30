<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services\Scopes;

use Illuminate\Support\Collection;
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
     * @return Collection<string, string>
     */
    public function all(): Collection
    {
        $resources = $this->resourceRepository->active();
        $actions = $this->actionRepository->active();

        $scopes = collect();

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $scopeName = ScopeName::from($resource, $action);

                $scopes->put(
                    $scopeName->value(),
                    ''
                );
            }
        }

        return $scopes;
    }
}
