<?php

// translations for N3XT0R/FilamentPassportUi
return [
    'navigation' => [
        'group' => 'API Management',
    ],
    'common' => [
        'updated_at' => 'updated at',
        'created_at' => 'created at',
        'scopes' => 'scopes',
    ],
    'resource' => [
        'global_action' => 'global action',
    ],
    'client_resource' => [
        'label' => 'OAuth Clients',
        'model_label' => 'Client',
        'plural_model_label' => 'Clients',
        'column' => [
            'name' => 'Name',
            'owner' => 'Owner',
            'grant_type' => 'Grant Type',
        ],
    ],
    'passport_scope_resource_resource' => [
        'label' => 'Resources',
        'model_label' => 'Resource',
        'plural_model_label' => 'Resources',
        'column' => [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ],
        'form' => [
            'name' => 'Name',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ],
    ],
    'passport_scope_actions_resource' => [
        'label' => 'Resource Actions',
        'model_label' => 'Resource Action',
        'plural_model_label' => 'Resource Actions',
        'column' => [
            'id' => 'ID',
            'name' => 'Action',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ],
        'form' => [
            'name' => 'Action',
            'description' => 'Description',
            'is_active' => 'Is Active',
        ],
    ],
];
