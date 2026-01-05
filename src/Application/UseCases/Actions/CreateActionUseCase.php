<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Services\ActionService;

readonly class CreateActionUseCase
{
    public function __construct(private ActionService $actionService)
    {
    }

    public function execute(array $data, ?Authenticatable $actor = null): PassportScopeAction
    {
        return $this->actionService->createAction($data, $actor);
    }
}
