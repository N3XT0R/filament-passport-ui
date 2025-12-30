<?php

return [
    /**
     * Model used as the owner of the OAuth2 clients.
     */
    'owner_model' => 'App\\Models\\User',
    'owner_label_attribute' => 'name',

    /**
     * Whether to use database stored scopes.
     */
    'use_database_scopes' => true,
];
