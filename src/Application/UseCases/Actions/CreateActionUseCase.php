<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Actions;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Services\ActionService;

readonly class CreateActionUseCase
{
    public function __construct(private ActionService $actionService)
    {
    }

    public function execute(array $data): PassportScopeAction
    {
        return $this->actionService->createAction($data);
    }
}
