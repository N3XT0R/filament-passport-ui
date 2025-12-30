<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;

class ActionRepository
{
    /**
     * Get all scope actions.
     * @return Collection<PassportScopeAction>
     */
    public function all(): Collection
    {
        return PassportScopeAction::query()->get();
    }

    /**
     * Get all active scope actions.
     * @return Collection<PassportScopeAction>
     */
    public function active(): Collection
    {
        return PassportScopeAction::query()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Find a scope action by its name.
     * @param string $name
     * @return PassportScopeAction|null
     */
    public function findByName(string $name): ?PassportScopeAction
    {
        return PassportScopeAction::query()
            ->where('name', $name)
            ->first();
    }
}
