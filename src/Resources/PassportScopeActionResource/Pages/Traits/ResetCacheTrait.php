<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources\PassportScopeActionResource\Pages\Traits;

use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;

trait ResetCacheTrait
{

    public function resetCache(): void
    {
        $repository = app(ActionRepositoryContract::class);
        if ($repository instanceof CachedActionRepositoryDecorator) {
            $repository->clearCache();
        }
    }
}
