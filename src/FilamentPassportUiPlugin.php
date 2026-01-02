<?php

namespace N3XT0R\FilamentPassportUi;

use Filament\Contracts\Plugin as FilamentPlugin;
use Filament\Panel;

class FilamentPassportUiPlugin implements FilamentPlugin
{


    public function getId(): string
    {
        return 'filament-passport-ui';
    }

    public function register(Panel $panel): void
    {
        $this->registerResources($panel);
    }

    protected function registerResources(Panel $panel): void
    {
        $resources = [
            Resources\ClientResource::class,
        ];

        if (config('filament-passport-ui.enable_scopes_management', true)) {
            $resources[] = Resources\PassportScopeResourceResource::class;
            $resources[] = Resources\PassportScopeActionResource::class;
        }


        $panel->resources($resources);
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
