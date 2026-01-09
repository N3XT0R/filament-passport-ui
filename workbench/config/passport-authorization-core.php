<?php

declare(strict_types=1);
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

    /**
     * Model mappings used by Passport and this package.
     *
     * Setting a value to `null` will fall back to Passport's default model.
     */
    'models' => [
        /**Model used to represent OAuth2 auth_codes.
         * Must be compatible with {@see \Laravel\Passport\AuthCode}.
         */
        'auth_code' => null,
        /**
         * Model used to represent OAuth2 clients.
         *
         * Must be compatible with {@see \N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client}.
         */
        'client' => null,

        /**
         * Model used to represent OAuth2 tokens.
         *
         * Must be compatible with {@see \Laravel\Passport\Token}.
         */
        'token' => null,

        /**
         * Model used to represent OAuth2 scopes.
         *
         * Must be compatible with {@see \Laravel\Passport\Scope}.
         */
        'scope' => null,

        /**
         * Model used to represent OAuth2 auth codes.
         *
         * Must be compatible with {@see \Laravel\Passport\RefreshToken}.
         */
        'refresh_token' => null,
    ],
];

