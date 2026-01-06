<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\UseCases\Cleanup;

use N3XT0R\FilamentPassportUi\Services\Scopes\ScopeRegistryService;

/**
 * Use case to clear the scope registry cache.
 */
readonly class ClearCacheUseCase
{
    public function __construct(private ScopeRegistryService $scopeRegistryService)
    {
    }

    public function execute(): void
    {
        $this->scopeRegistryService->clearCache();
    }
}