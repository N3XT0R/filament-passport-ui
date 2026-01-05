<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
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
     * @return PassportScopeResource
     */
    public function createResource(array $data, ?Authenticatable $actor = null): PassportScopeResource
    {
        $resource = $this->resourceRepository->createResource($data);

        if ($actor) {
            activity('oauth_scope_resource')
                ->by($actor)
                ->withProperties([
                    'resource_id' => $resource->getKey(),
                    'resource_name' => $resource->getAttribute('name'),
                    'description' => $resource->getAttribute('description'),
                    'is_active' => $resource->getAttribute('is_active'),
                ])
                ->log('OAuth scope resource created');
        }

        return $resource;
    }

    /**
     * Update a scope resource.
     * @param PassportScopeResource $resource
     * @param array $data
     * @param Authenticatable|null $actor
     * @return PassportScopeResource
     */
    public function updateResource(
        PassportScopeResource $resource,
        array $data,
        ?Authenticatable $actor = null
    ): PassportScopeResource {
        $resource = $this->resourceRepository->updateResource($resource, $data);

        if ($actor) {
            activity('oauth_scope_resource')
                ->by($actor)
                ->withProperties([
                    'resource_id' => $resource->getKey(),
                    'resource_name' => $resource->getAttribute('name'),
                    'description' => $resource->getAttribute('description'),
                    'is_active' => $resource->getAttribute('is_active'),
                ])
                ->log('OAuth scope resource updated');
        }

        return $resource;
    }

    public function deleteResource(PassportScopeResource $resource, ?Authenticatable $actor = null): bool
    {
        $result = $this->resourceRepository->deleteResource($resource);

        if ($result && $actor) {
            activity('oauth_scope_resource')
                ->by($actor)
                ->withProperties([
                    'resource_id' => $resource->getKey(),
                    'resource_name' => $resource->getAttribute('name'),
                    'description' => $resource->getAttribute('description'),
                    'is_active' => $resource->getAttribute('is_active'),
                ])
                ->log('OAuth scope resource deleted');
        }

        return $result;
    }
}
