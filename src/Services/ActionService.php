<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;

readonly class ActionService
{
    public function __construct(private ActionRepository $actionRepository)
    {
    }

    /**
     * Delete a scope action.
     * @param PassportScopeAction $action
     * @param Authenticatable|null $actor
     * @return bool
     */
    public function deleteAction(PassportScopeAction $action, ?Authenticatable $actor = null): bool
    {
        $result = $this->actionRepository->deleteAction($action);

        if ($result && $actor) {
            activity('oauth_scope_action')
                ->by($actor)
                ->withProperties([
                    'action_id' => $action->getKey(),
                    'action_name' => $action->getAttribute('name'),
                    'resource_id' => $action->getAttribute('resource_id'),
                    'description' => $action->getAttribute('description'),
                    'is_active' => $action->getAttribute('is_active'),
                ])
                ->log('OAuth scope action deleted');
        }

        return $result;
    }

    /**
     * Create a new scope action.
     * @param array $data
     * @param Authenticatable|null $actor
     * @return PassportScopeAction
     */
    public function createAction(array $data, ?Authenticatable $actor = null): PassportScopeAction
    {
        $action = $this->actionRepository->createAction($data);

        if ($actor) {
            activity('oauth_scope_action')
                ->by($actor)
                ->withProperties([
                    'action_id' => $action->getKey(),
                    'action_name' => $action->getAttribute('name'),
                    'resource_id' => $action->getAttribute('resource_id'),
                    'description' => $action->getAttribute('description'),
                    'is_active' => $action->getAttribute('is_active'),
                ])
                ->log('OAuth scope action created');
        }

        return $action;
    }

    /**
     * Update an existing scope action.
     * @param PassportScopeAction $action
     * @param array $data
     * @param Authenticatable|null $actor
     * @return PassportScopeAction
     */
    public function updateAction(
        PassportScopeAction $action,
        array $data,
        ?Authenticatable $actor = null
    ): PassportScopeAction {
        $updatedAction = $this->actionRepository->updateAction($action, $data);

        if ($actor) {
            activity('oauth_scope_action')
                ->by($actor)
                ->withProperties([
                    'action_id' => $updatedAction->getKey(),
                    'action_name' => $updatedAction->getAttribute('name'),
                    'resource_id' => $action->getAttribute('resource_id'),
                    'description' => $action->getAttribute('description'),
                    'is_active' => $action->getAttribute('is_active'),
                ])
                ->log('OAuth scope action updated');
        }

        return $updatedAction;
    }
}
