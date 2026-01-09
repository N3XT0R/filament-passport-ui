<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Resources;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Events\PassportScopeResource\ResourceDeletedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Services\ResourceService;

/**
 * Delete an existing Passport Scope Resource Use Case.
 */
readonly class DeleteResourceUseCase
{
    public function __construct(private ResourceService $resourceService)
    {
    }

    public function execute(PassportScopeResource $resource, ?Authenticatable $actor = null): bool
    {
        $result = $this->resourceService->deleteResource(
            resource: $resource,
            actor: $actor
        );

        if ($result) {
            ResourceDeletedEvent::dispatch($resource, $actor);
        }

        return $result;
    }
}
