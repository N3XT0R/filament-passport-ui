<?php

use Filament\Support\Icons\Heroicon;

return [
    /**
     * Model used as the owner of the OAuth2 clients.
     */
    'owner_model' => '\\App\\Models\\User',
    'owner_label_attribute' => 'name',

    /**
     * Whether to use database stored scopes.
     */
    'use_database_scopes' => true,

    /**
     * Navigation settings for the ClientResource.
     */
    'navigation' => [
        'client_resource' => [
            'group' => 'filament-passport-ui::passport-ui.navigation.group',
            'icon' => Heroicon::OutlinedKey,
        ],
    ],
    /**
     * Model mappings used by Passport and this package.
     *
     * Setting a value to `null` will fall back to Passport's default model.
     */
    'models' => [
        /**
         * Model used to represent OAuth2 clients.
         *
         * Must be compatible with {@see \Laravel\Passport\Client}.
         */
        'client' => Laravel\Passport\Client::class,

        /**
         * Model used to represent OAuth2 clients.
         *
         * Must be compatible with {@see \Laravel\Passport\Token}.
         */
        'token' => Laravel\Passport\Token::class,

        /**
         * Model used to represent OAuth2 scopes.
         *
         * Must be compatible with {@see \Laravel\Passport\Scope}.
         */
        'scope' => Laravel\Passport\Scope::class,
    ],
    /**
     * Cache settings.
     */
    'cache' => [
        /**
         * Whether to cache the scopes.
         */
        'enabled' => true,

        /**
         * The cache ttl in seconds.
         */
        'ttl' => 3600,
    ],

    /**
     * OAuth client settings and ui options.
     */
    'oauth' => [
        'allowed_grant_types' => [
            'authorization_code',
            'client_credentials',
            'password',
            'personal_access',
            'implicit',
            'device',
        ],
    ],
];
