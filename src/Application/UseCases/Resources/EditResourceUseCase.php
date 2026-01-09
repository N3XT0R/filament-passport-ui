<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Resources;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Events\PassportScopeResource\ResourceUpdatedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Services\ResourceService;

/**
 * Update an existing Passport Scope Resource Use Case.
 */
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
        $result = $this->resourceService->updateResource(
            resource: $resource,
            data: $data,
            actor: $actor
        );

        ResourceUpdatedEvent::dispatch($result, $actor);

        return $result;
    }
}
