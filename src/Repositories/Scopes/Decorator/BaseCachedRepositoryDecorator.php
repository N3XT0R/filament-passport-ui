<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator;

use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Support\Contracts\ClearsCacheContract;

abstract class BaseCachedRepositoryDecorator implements ClearsCacheContract
{
    protected const array CACHE_TAGS = [];


    public function clearCache(): void
    {
        Cache::tags(static::CACHE_TAGS)->flush();
    }
}
