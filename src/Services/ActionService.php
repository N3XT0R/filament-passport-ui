<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
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
                ])
                ->log('OAuth scope action deleted');
        }

        return $result;
    }
}
