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
            Resources\TokenResource::class,
        ];

        if (config('filament-passport-ui.enable_scopes_management', true)) {
            $resources[] = Resources\PassportScopeResourceResource::class;
            $resources[] = Resources\PassportScopeActionsResource::class;
        }


        $panel->resources($resources);
    }

    /**
     * Bootstrap any plugin panel services if any exists
     * @param Panel $panel
     * @return void
     */
    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * Create a new plugin instance from the container.
     * @note This method assumes the plugin is registered with Filament
     * and is possible to override/extend the plugin class via DI.
     * @return static
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Get the plugin instance from the Filament container.
     * @return static
     */
    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(static::make()->getId());

        return $plugin;
    }
}
