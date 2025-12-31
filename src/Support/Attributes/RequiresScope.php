<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
final readonly class RequiresScope
{
    /**
     * @param string[] $scopes
     */
    public function __construct(
        public array $scopes
    ) {
    }
}
