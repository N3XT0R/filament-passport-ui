<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Application\StateResolvers\Common;

use Illuminate\Support\Str;

class FormatHeadlineState
{
    public function execute(string $state): string
    {
        return Str::headline($state);
    }
}
