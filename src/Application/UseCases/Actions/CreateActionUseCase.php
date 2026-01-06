<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Events\PassportScopeAction\ActionCreatedEvent;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Services\ActionService;

/**
 * Create Action Use Case for OAuth Passport Scope Actions.
 */
readonly class CreateActionUseCase
{
    public function __construct(private ActionService $actionService)
    {
    }

    public function execute(array $data, ?Authenticatable $actor = null): PassportScopeAction
    {
        $result = $this->actionService->createAction($data, $actor);
        if ($result->exists) {
            ActionCreatedEvent::dispatch($result, $actor);
        }

        return $result;
    }
}
