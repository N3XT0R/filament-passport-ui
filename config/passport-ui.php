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
        'allowed_grant_types' => \N3XT0R\FilamentPassportUi\Enum\OAuthClientType::casesValues(),
    ],
];
