<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Scopes;

namespace N3XT0R\FilamentPassportUi\Support\Scopes;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

final readonly class GrantedScopesByResourceProvider
{
    public function __construct(
        protected GrantService $grantService,
    ) {
    }

    /**
     * Get granted scopes grouped by resource for the given tokenable.
     * @param HasPassportScopeGrantsInterface|null $tokenable
     * @return Collection<string, Collection<string>>
     */
    public function get(?HasPassportScopeGrantsInterface $tokenable): Collection
    {
        if ($tokenable === null) {
            return collect();
        }

        return $this->grantService
            ->getTokenableGrantsAsScopes($tokenable)
            ->groupBy(fn(string $scope) => explode(':', $scope, 2)[0])
            ->map(fn(Collection $scopes) => $scopes->values());
    }
}