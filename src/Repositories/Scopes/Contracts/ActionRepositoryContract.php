<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Contracts\IsMigratedContract;

interface ActionRepositoryContract extends IsMigratedContract
{
    /**
     * Get all scope actions.
     * @return Collection<PassportScopeAction>
     */
    public function all(): Collection;

    /**
     * Get all active scope actions.
     * @return Collection<PassportScopeAction>
     */
    public function active(): Collection;

    /**
     * Find a scope action by its name.
     * @param string $name
     * @return PassportScopeAction|null
     */
    public function findByName(string $name): ?PassportScopeAction;
}
