<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Contracts\IsMigratedContract;

interface ResourceRepositoryContract extends IsMigratedContract
{
    /**
     * Get all scope resources.
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get all active scope resources.
     * @return Collection<PassportScopeResource>
     */
    public function active(): Collection;

    /**
     * Find a scope resource by its name.
     * @param string $name
     * @return PassportScopeResource|null
     */
    public function findByName(string $name): ?PassportScopeResource;

    /**
     * Create a new scope resource.
     * @param array $data
     * @return PassportScopeResource
     */
    public function createResource(array $data): PassportScopeResource;

    /**
     * Delete a scope resource.
     * @param PassportScopeResource $resource
     * @return bool
     */
    public function deleteResource(PassportScopeResource $resource): bool;

    /**
     * Update a scope resource.
     * @param PassportScopeResource $resource
     * @param array $data
     * @return PassportScopeResource
     */
    public function updateResource(PassportScopeResource $resource, array $data): PassportScopeResource;
}
