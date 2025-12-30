<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\ValueObjects\Scopes;

use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;

class ScopeName
{
    private function __construct(
        private readonly string $value
    ) {}

    public static function from(
        PassportScopeResource $resource,
        PassportScopeAction $action
    ): self {
        return new self(
            $resource->name . '.' . $action->name
        );
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
