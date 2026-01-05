<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Resources;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Services\ResourceService;

readonly class DeleteResourceUseCase
{
    public function __construct(private ResourceService $resourceService)
    {
    }

    public function execute(PassportScopeResource $resource, ?Authenticatable $actor = null): bool
    {
        return $this->resourceService->deleteResource(
            resource: $resource,
            actor: $actor
        );
    }
}
