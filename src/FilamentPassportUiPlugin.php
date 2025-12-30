<?php

namespace N3XT0R\FilamentPassportUi;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentPassportUiPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-passport-ui';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
