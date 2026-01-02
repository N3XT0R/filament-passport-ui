<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Repositories;

use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Config\Repository;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;

/**
 * Configuration Repository for Filament Passport UI
 */
readonly class ConfigRepository
{
    private const string CONFIG_ROOT = 'passport-ui.';


    public function __construct(private Repository $config)
    {
    }

    /**
     * Get active OAuth client types based on configuration
     * @return OAuthClientType[]
     */
    public function getAllowedGrantTypes(): array
    {
        $values = $this->config->get(
            self::CONFIG_ROOT . 'oauth.allowed_grant_types',
            []
        );

        $types = [];

        foreach ($values as $value) {
            $types[] = OAuthClientType::from($value);
        }

        return $types;
    }

    /**
     * Get the owner model class name for OAuth clients
     * @return string
     */
    public function getOwnerModel(): string
    {
        return (string)$this->config->get(self::CONFIG_ROOT . 'owner_model', '\\App\\Models\\User');
    }

    /**
     * Get the attribute used as label for owner relationships for OAuth clients
     * @return string
     */
    public function getOwnerLabelAttribute(): string
    {
        return (string)$this->config->get(self::CONFIG_ROOT . 'owner_label_attribute', 'name');
    }

    /**
     * Get the navigation group name for OAuth Management
     * @param string $default
     * @return string
     */
    public function getNavigationGroup(string $default = 'OAuth Management'): string
    {
        return (string)$this->config->get(self::CONFIG_ROOT . 'navigation.group', $default);
    }

    /**
     * Get the navigation icon for a given resource
     * @param string $resource
     * @param string|ScalableIcon $icon
     * @return string|ScalableIcon
     */
    public function getNavigationIcon(
        string $resource,
        string|ScalableIcon $icon = Heroicon::OutlinedKey
    ): string|ScalableIcon {
        return $this->config->get(self::CONFIG_ROOT . 'navigation.' . $resource . '.icon', $icon);
    }

    /**
     * Check if database scopes are used for Passport
     * @return bool
     */
    public function isUsingDatabaseScopes(): bool
    {
        return (bool)$this->config->get(self::CONFIG_ROOT . 'use_database_scopes', false);
    }

}
