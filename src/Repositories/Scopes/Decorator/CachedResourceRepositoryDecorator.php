<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;

final class CachedResourceRepositoryDecorator extends BaseCachedRepositoryDecorator implements
    ResourceRepositoryContract
{
    protected const array CACHE_TAGS = [
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
            callback: fn() => $this->innerRepository->all(),
        );
    }

    public function active(): Collection
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: 'passport.scopes.resources.active',
            ttl: $this->ttl(),
            callback: fn() => $this->innerRepository->active(),
        );
    }

    public function findByName(string $name): ?PassportScopeResource
    {
        return Cache::tags(self::CACHE_TAGS)->remember(
            key: "passport.scopes.resources.by-name.{$name}",
            ttl: $this->ttl(),
            callback: fn() => $this->innerRepository->findByName($name),
        );
    }

    public function isMigrated(): bool
    {
        return $this->innerRepository->isMigrated();
    }

    public function createResource(array $data): PassportScopeResource
    {
        $result = $this->innerRepository->createResource($data);
        $this->clearCache();
        return $result;
    }

    public function deleteResource(PassportScopeResource $resource): bool
    {
        $result = $this->innerRepository->deleteResource($resource);
        $this->clearCache();
        return $result;
    }

    public function updateResource(array $data): PassportScopeResource
    {
        $result = $this->innerRepository->updateResource($data);
        $this->clearCache();
        return $result;
    }
}
