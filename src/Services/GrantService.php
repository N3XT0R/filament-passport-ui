<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\FilamentPassportUi\Models\PassportScopeGrant;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\FilamentPassportUi\Support\OAuth\ValueObjects\Scope;

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
     * @param HasPassportScopeGrantsInterface&Model $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @param Authenticatable|null $actor
     * @return PassportScopeGrant
     */
    public function grantScopeToTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
        ?Authenticatable $actor = null,
    ): PassportScopeGrant {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            throw new \InvalidArgumentException("Resource '{$resourceName}' not found.");
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            throw new \InvalidArgumentException("Action '{$actionName}' not found.");
        }

        $result = $this->scopeGrantRepository->createOrUpdateScopeGrantForTokenable(
            $tokenable,
            $resource->getKey(),
            $action->getKey(),
        );

        if ($result && $actor) {
            activity('oauth')
                ->performedOn($tokenable)
                ->causedBy($actor)
                ->withProperties([
                    'tokenable_type' => $tokenable->getMorphClass(),
                    'tokenable_id' => $tokenable->getKey(),
                    'granted_scope' => new Scope($resourceName, $actionName)->toString(),
                ])
                ->log('OAuth scope grant given to tokenable');
        }


        return $result;
    }

    /**
     * Revoke a scope from the given tokenable model.
     * @param Model&HasPassportScopeGrantsInterface $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @return bool
     */
    public function revokeScopeFromTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
        ?Authenticatable $actor = null,
    ): bool {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            throw new \InvalidArgumentException("Resource '{$resourceName}' not found.");
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            throw new \InvalidArgumentException("Action '{$actionName}' not found.");
        }

        $result = $this->scopeGrantRepository->deleteScopeGrantForTokenable(
            $tokenable,
            $resource->getKey(),
            $action->getKey(),
        );

        if ($result && $actor) {
            activity('oauth')
                ->performedOn($tokenable)
                ->causedBy($actor)
                ->withProperties([
                    'tokenable_type' => $tokenable->getMorphClass(),
                    'tokenable_id' => $tokenable->getKey(),
                    'revoked_scope' => new Scope($resourceName, $actionName)->toString(),
                ])
                ->log('OAuth scope grant revoked from tokenable');
        }


        return $result;
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

        return app(ScopeGrantRepository::class)->tokenableHasScopeGrant(
            tokenable: $tokenable,
            resourceId: $resource->getKey(),
            actionId: $action->getKey(),
        );
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

    /**
     * Get all grants of the tokenable as scope strings.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @return Collection<string>
     */
    public function getTokenableGrantsAsScopes(HasPassportScopeGrantsInterface $tokenable): Collection
    {
        $grants = $this->scopeGrantRepository->getTokenableGrants($tokenable);


        return $grants->map(fn(PassportScopeGrant $grant) => new Scope(
            $grant->resource->getAttribute('name'), $grant->action->getAttribute('name')
        )->toString());
    }


    /**
     * Give multiple grants to the tokenable based on the provided scopes.
     * @param HasPassportScopeGrantsInterface&Model $tokenable
     * @param array $scopes
     * @param Authenticatable|null $actor
     * @return void
     */
    public function giveGrantsToTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        array $scopes,
        ?Authenticatable $actor = null,
    ): void {
        foreach ($scopes as $scopeString) {
            $scope = Scope::fromString($scopeString);

            if ($this->tokenableHasGrantToScope($tokenable, $scopeString)) {
                continue;
            }


            $this->grantScopeToTokenable(
                $tokenable,
                $scope->resource,
                $scope->action,
            );
        }

        if ($actor) {
            activity('oauth')
                ->performedOn($tokenable)
                ->causedBy($actor)
                ->withProperties([
                    'tokenable_type' => $tokenable->getMorphClass(),
                    'tokenable_id' => $tokenable->getKey(),
                    'granted_scopes' => $scopes,
                ])
                ->log('OAuth scope grants given to tokenable');
        }
    }

    /**
     * Revoke multiple grants from the tokenable based on the provided scopes.
     * @param Model&HasPassportScopeGrantsInterface $tokenable
     * @param array $scopes
     * @return void
     */
    public function revokeGrantsFromTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        array $scopes,
        ?Authenticatable $actor = null,
    ): void {
        foreach ($scopes as $scopeString) {
            $scope = Scope::fromString($scopeString);

            if (!$this->tokenableHasGrantToScope($tokenable, $scopeString)) {
                continue;
            }

            $this->revokeScopeFromTokenable(
                $tokenable,
                $scope->resource,
                $scope->action,
            );
        }

        if ($actor) {
            activity('oauth')
                ->performedOn($tokenable)
                ->causedBy($actor)
                ->withProperties([
                    'tokenable_type' => $tokenable->getMorphClass(),
                    'tokenable_id' => $tokenable->getKey(),
                    'revoked_scopes' => $scopes,
                ])
                ->log('OAuth scope grants revoked from tokenable');
        }
    }

    /**
     * Upsert grants for the tokenable based on the provided scopes.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param array $scopes
     * @return void
     */
    public function upsertGrantsForTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        array $scopes,
        ?Authenticatable $actor = null,
    ): void {
        $existingGrants = $this->getTokenableGrantsAsScopes($tokenable)->toArray();

        $scopesToRevoke = array_diff($existingGrants, $scopes);
        $scopesToGrant = array_diff($scopes, $existingGrants);

        $this->revokeGrantsFromTokenable($tokenable, $scopesToRevoke);
        $this->giveGrantsToTokenable($tokenable, $scopesToGrant);

        if ($actor) {
            activity('oauth')
                ->performedOn($tokenable)
                ->causedBy($actor)
                ->withProperties([
                    'tokenable_type' => $tokenable->getMorphClass(),
                    'tokenable_id' => $tokenable->getKey(),
                    'upserted_scopes' => $scopes,
                ])
                ->log('OAuth scope grants upserted for tokenable');
        }
    }
}
