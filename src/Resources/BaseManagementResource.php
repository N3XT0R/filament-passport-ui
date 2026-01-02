<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Resources;

use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use UnitEnum;

abstract class BaseManagementResource extends Resource
{
    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __(
            app(ConfigRepository::class)
                ->getNavigationGroup(static::$navigationGroup)
        );
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        $item = Str::afterLast(static::class, '\\');
        return app(ConfigRepository::class)
            ->getNavigationIcon($item, static::$navigationIcon);
    }
}
