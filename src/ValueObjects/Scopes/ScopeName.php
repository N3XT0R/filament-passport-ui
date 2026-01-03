<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\ValueObjects\Scopes;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;

class ScopeName
{
    private function __construct(
        private readonly string $value,
        private readonly ?string $description = null,
    ) {
    }

    public static function from(
        PassportScopeResource $resource,
        PassportScopeAction $action
    ): self {
        return new self(
            $resource->getAttribute('name') . ':' . $action->getAttribute('name'),
            trim($resource->getAttribute('description')) . ': ' . $action->getAttribute('description')
        );
    }

    public function value(): string
    {
        return $this->value;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
