<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator;

use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;

class CachedResourceRepositoryDecorator implements ResourceRepositoryContract
{
    public function __construct(
        protected ResourceRepositoryContract $innerRepository,
    ) {
    }

    public function all(): Collection
    {
        return $this->innerRepository->all();
    }

    public function active(): Collection
    {
        return $this->innerRepository->active();
    }

    public function findByName(string $name): ?PassportScopeResource
    {
        return $this->innerRepository->findByName($name);
    }
}
