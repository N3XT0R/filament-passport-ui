<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;

class CachedActionRepositoryDecorator extends BaseCachedRepositoryDecorator implements ActionRepositoryContract
{
    protected const array CACHE_TAGS = [
        'passport',
        'passport.scopes',
        'passport.scopes.actions',
    ];

    public function __construct(
        private readonly ActionRepositoryContract $innerRepository,
    ) {
    }

    public function all(): Collection
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: 'passport.scopes.actions.all',
            ttl: $this->ttl(),
            callback: fn () => $this->innerRepository->all(),
        );
    }

    public function active(): Collection
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: 'passport.scopes.actions.active',
            ttl: $this->ttl(),
            callback: fn () => $this->innerRepository->active(),
        );
    }

    public function findByName(string $name): ?PassportScopeAction
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: "passport.scopes.actions.by-name.{$name}",
            ttl: $this->ttl(),
            callback: fn () => $this->innerRepository->findByName($name),
        );
    }

    private function ttl(): int
    {
        return (int)config(
            'passport-ui.cache.ttl',
            3600
        );
    }

}
