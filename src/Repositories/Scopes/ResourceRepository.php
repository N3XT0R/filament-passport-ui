<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;

class ResourceRepository
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
}
