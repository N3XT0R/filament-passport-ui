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
        return app(ConfigRepository::class)
            ->getNavigationIcon(self::getResourceName(), static::$navigationIcon);
    }

    public static function getLabel(): ?string
    {
        return self::getTranslationByKey('label');
    }

    public static function getPluralLabel(): ?string
    {
        return self::getTranslationByKey('plural_model_label');
    }

    protected static function getTranslationByKey(string $key): string
    {
        $item = self::getResourceName();

        return __("filament-passport-ui::passport-ui.{$item}.{$key}");
    }

    private static function getResourceName(): string
    {
        return Str::snake(Str::afterLast(static::class, '\\'));
    }

}
