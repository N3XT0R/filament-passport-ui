<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;

final class CachedResourceRepositoryDecorator implements ResourceRepositoryContract
{
    private const array CACHE_TAGS = [
        'passport',
        'passport.scopes',
        'passport.scopes.resources',
    ];

    public function __construct(
        private readonly ResourceRepositoryContract $innerRepository,
    ) {
    }

    public function all(): Collection
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: 'passport.scopes.resources.all',
            ttl: $this->ttl(),
            callback: fn () => $this->innerRepository->all(),
        );
    }

    public function active(): Collection
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: 'passport.scopes.resources.active',
            ttl: $this->ttl(),
            callback: fn () => $this->innerRepository->active(),
        );
    }

    public function findByName(string $name): ?PassportScopeResource
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: "passport.scopes.resources.by-name.{$name}",
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
