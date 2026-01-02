<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\FilamentPassportUi\Support\OAuth\Scope;

readonly class GrantService
{
    public function __construct(
        private ScopeGrantRepository $scopeGrantRepository,
        private ResourceRepositoryContract $resourceRepository,
        private ActionRepositoryContract $actionRepository,
    ) {
    }

    /**
     * Grant a scope to the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @return PassportScopeGrant
     */
    public function grantScopeToTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
    ): PassportScopeGrant {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            throw new \InvalidArgumentException("Resource '{$resourceName}' not found.");
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            throw new \InvalidArgumentException("Action '{$actionName}' not found.");
        }

        return $this->scopeGrantRepository->createOrUpdateScopeGrantForTokenable(
            $tokenable,
            $resource->getKey(),
            $action->getKey(),
        );
    }

    /**
     * Check if the tokenable has a specific grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @return bool
     */
    public function tokenableHasGrant(
        HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
    ): bool {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            return false;
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            return false;
        }

        return $tokenable->passportScopeGrants()
            ->where('resource_id', $resource->getKey())
            ->where('action_id', $action->getKey())
            ->exists();
    }

    /**
     * Check if the tokenable has a grant to a specific scope.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string $scopeString
     * @return bool
     */
    public function tokenableHasGrantToScope(
        HasPassportScopeGrantsInterface $tokenable,
        string $scopeString,
    ): bool {
        $scope = Scope::fromString($scopeString);

        return $this->tokenableHasGrant(
            $tokenable,
            $scope->resource,
            $scope->action,
        );
    }
}
