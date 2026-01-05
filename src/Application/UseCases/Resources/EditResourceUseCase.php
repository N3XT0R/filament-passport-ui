<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Resources;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Services\ResourceService;

readonly class EditResourceUseCase
{
    public function __construct(private ResourceService $resourceService)
    {
    }

    public function execute(
        PassportScopeResource $resource,
        array $data,
        ?Authenticatable $actor = null
    ): PassportScopeResource {
        return $this->resourceService->updateResource(
            resource: $resource,
            data: $data,
            actor: $actor
        );
    }
}
