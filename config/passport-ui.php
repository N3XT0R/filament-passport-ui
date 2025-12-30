<?php

return [
    /**
     * Model used as the owner of the OAuth2 clients.
     */
    'owner_model' => 'App\\Models\\User',
    'owner_label_attribute' => 'name', // attribute / accessor to use as the owner's label in the UI
];
