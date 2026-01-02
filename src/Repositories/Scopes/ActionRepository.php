<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;

class ActionRepository implements ActionRepositoryContract
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

    /**
     * Check if the scope actions table is migrated.
     * @return bool
     */
    public function isMigrated(): bool
    {
        return Schema::hasTable('passport_scope_actions');
    }

    /**
     * Get the total count of scope actions.
     * @return int
     */
    public function count(): int
    {
        return PassportScopeAction::query()->count();
    }
}
