<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Icons\Heroicon;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use Illuminate\Contracts\Config\Repository;

/**
 * Configuration Repository for Filament Passport UI
 */
readonly class ConfigRepository
{
    private const string CONFIG_ROOT = 'passport-ui.oauth.';


    public function __construct(private Repository $config)
    {
    }

    /**
     * Get active OAuth client types based on configuration
     * @return OAuthClientType[]
     */
    public function getAllowedGrantTypes(): array
    {
        $allowed = $this->config->get(self::CONFIG_ROOT . 'allowed_grant_types', []);
        foreach ($allowed as $value) {
            $allowed[] = OAuthClientType::from($value);
        }

        return $allowed;
    }

    /**
     * Get the owner model class name
     * @return string
     */
    public function getOwnerModel(): string
    {
        return (string)$this->config->get('passport-ui.owner_model', '\\App\\Models\\User');
    }

    /**
     * Get the attribute used as label for owners
     * @return string
     */
    public function getOwnerLabelAttribute(): string
    {
        return (string)$this->config->get('passport-ui.owner_label_attribute', 'name');
    }

    /**
     * Get the navigation group name for OAuth Management
     * @param string $default
     * @return string
     */
    public function getNavigationGroup(string $default = 'OAuth Management'): string
    {
        return (string)$this->config->get('passport-ui.navigation.group', $default);
    }

    /**
     * Get the navigation icon for OAuth Management
     * @param string|ScalableIcon $icon
     * @return string|ScalableIcon
     */
    public function getNavigationIcon(string|ScalableIcon $icon = Heroicon::OutlinedKey): string|ScalableIcon
    {
        return $this->config->get('passport-ui.navigation.icon', $icon);
    }

    /**
     * Check if database scopes are used
     * @return bool
     */
    public function isUsingDatabaseScopes(): bool
    {
        return (bool)$this->config->get('passport-ui.use_database_scopes', false);
    }

}
