<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;

class ActionRepository
{
    public function all(): Collection
    {
        return PassportScopeAction::query()->get();
    }

    public function active(): Collection
    {
        return PassportScopeAction::query()
            ->where('is_active', true)
            ->get();
    }

    public function findByName(string $name): ?PassportScopeAction
    {
        return PassportScopeAction::query()
            ->where('name', $name)
            ->first();
    }
}
