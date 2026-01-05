<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;

readonly class ResourceService
{
    public function __construct(private ResourceRepository $resourceRepository)
    {
    }

    /**
     * Create a new scope resource.
     * @param array $data
     * @param Authenticatable|null $actor
     * @return \N3XT0R\FilamentPassportUi\Models\PassportScopeResource
     */
    public function createResource(array $data, ?Authenticatable $actor = null)
    {
        $resource = $this->resourceRepository->createResource($data);

        if ($actor) {
            activity('oauth_scope_resource')
                ->by($actor)
                ->withProperties([
                    'resource_id' => $resource->getKey(),
                    'resource_name' => $resource->getAttribute('name'),
                ])
                ->log('OAuth scope resource created');
        }

        return $resource;
    }
}
