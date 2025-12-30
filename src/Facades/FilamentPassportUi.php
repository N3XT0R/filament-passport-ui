<?php

namespace N3XT0R\FilamentPassportUi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \N3XT0R\FilamentPassportUi\FilamentPassportUi
 */
class FilamentPassportUi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \N3XT0R\FilamentPassportUi\FilamentPassportUi::class;
    }
}
