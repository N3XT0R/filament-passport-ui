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
}
