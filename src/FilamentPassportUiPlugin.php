<?php

namespace N3XT0R\FilamentPassportUi;

use Filament\Contracts\Plugin as FilamentPlugin;
use BezhanSalleh\PluginEssentials\Concerns\Plugin as Essentials;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;

class FilamentPassportUiPlugin implements FilamentPlugin
{

    use Essentials\BelongsToParent;
    use Essentials\HasGlobalSearch;
    use Essentials\HasLabels;
    use Essentials\HasNavigation;
    use Essentials\HasPluginDefaults;

    public function getId(): string
    {
        return 'filament-passport-ui';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            Resources\ClientResource::class,
        ]);
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

    protected function getPluginDefaults(): array
    {
        return [
            'navigationGroup' => __('filament-shield::filament-shield.navigation.group'),
            'navigationIcon' => 'heroicon-o-lock-closed',
            'resources' => [
                Resources\ClientResource::class => [
                    'modelLabel' => __('filament-passport-ui:filament-passport-ui.client_resource.model_label'),
                    'pluralModelLabel' => __(
                        'filament-passport-ui:filament-passport-ui.client_resource.plural_model_label'
                    ),
                    'navigationIcon' => 'heroicon-o-users',
                ],
            ],
        ];
    }
}
