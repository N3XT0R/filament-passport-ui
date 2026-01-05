<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;

class ResourceRepository implements ResourceRepositoryContract
{
    public function all(): Collection
    {
        return PassportScopeResource::query()->get();
    }

    /**
     * Get all active scope resources.
     * @return Collection<PassportScopeResource>
     */
    public function active(): Collection
    {
        return PassportScopeResource::query()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Count all scope resources.
     * @return int
     */
    public function count(): int
    {
        return PassportScopeResource::query()->count();
    }

    /**
     * Find a scope resource by its name.
     * @param string $name
     * @return PassportScopeResource|null
     */
    public function findByName(string $name): ?PassportScopeResource
    {
        return PassportScopeResource::query()
            ->where('name', $name)
            ->first();
    }

    public function isMigrated(): bool
    {
        return Schema::hasTable('passport_scope_resources');
    }

    public function createResource(array $data): PassportScopeResource
    {
        return PassportScopeResource::query()->create($data);
    }

    public function deleteResource(PassportScopeResource $resource): bool
    {
        return $resource->delete();
    }

    public function updateResource(array $data): PassportScopeResource
    {
        $resource = $this->findByName($data['name']);
        if (!$resource) {
            throw new \RuntimeException("Resource not found");
        }

        $resource->update($data);
        return $resource;
    }
}
