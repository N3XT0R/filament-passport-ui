<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Icons\Heroicon;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ConfigRepository as BaseRepository;

/**
 * Configuration Repository for Filament Passport UI
 */
readonly class ConfigRepository extends BaseRepository
{
    private const string CONFIG_ROOT = 'passport-ui.';

    /**
     * Get the navigation icon for a given resource
     * @param string $resource
     * @param string|ScalableIcon|null $icon
     * @return string|ScalableIcon|null
     */
    public function getNavigationIcon(
        string $resource,
        string|ScalableIcon|null $icon = null
    ): string|ScalableIcon|null {
        if (null === $icon) {
            $icon = Heroicon::OutlinedKey;
        }
        return $this->config->get(self::CONFIG_ROOT . 'navigation.' . $resource . '.icon', $icon);
    }

    /**
     * Get the navigation group name for OAuth Management
     * @param string|null $default
     * @return string
     */
    public function getNavigationGroup(?string $default = null): string
    {
        if (null === $default) {
            $default = 'OAuth Management';
        }
        return (string)$this->config->get(self::CONFIG_ROOT . 'navigation.group', $default);
    }

}
