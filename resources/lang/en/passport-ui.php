<?php

// translations for N3XT0R/FilamentPassportUi
return [
    'navigation' => [
        'group' => 'API Management',
    ],
    'common' => [
        'id' => 'ID',
        'name' => 'Name',
        'description' => 'Description',
        'updated_at' => 'Updated at',
        'created_at' => 'Created at',
        'expires_at' => 'Expires At',
        'scopes' => 'scopes',
        'none' => 'none',
        'is_active' => 'Is Active',
    ],
    'resource' => [
        'global_action' => 'global action',
    ],
    'client_resource' => [
        'label' => 'OAuth Clients',
        'model_label' => 'Client',
        'plural_model_label' => 'Clients',
        'form' => [
            'owner_hint' => 'The owner of this client. Used to associate the client with a user.',
            'secret_label' => 'Client Secret',
            'secret_helper_text' => 'This is the client secret. Make sure to copy it now as it will not be shown again.',
            'revoke_label' => 'Revoke Client',
            'wizard' => [
                'steps' => [
                    'client' => [
                        'label' => 'Client Details',
                        'description' => 'Fill in the client details.',
                    ],
                    'user_permission' => [
                        'label' => 'User Permission',
                        'description' => 'Assign user permissions to the client.',
                    ],
                ],
            ],
        ],
        'column' => [
            'name' => 'Name',
            'owner' => 'Owner',
            'grant_type' => 'Grant Type',
            'last_login' => 'Last Login',
            'revoked' => 'Revoked',
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
            'is_global' => 'Is Global',
        ],
        'form' => [
            'name' => 'Action',
            'description' => 'Description',
            'is_active' => 'Is Active',
            'resource_id' => 'Resource',
            'resource_id_helper_text' => 'Select the resource this action belongs to. Leave empty to make it a global action.',
        ],
        'header_action' => [
            'create' => 'Create Resource Action',
        ],
    ],
    'token_resource' => [
        'label' => 'Tokens',
        'model_label' => 'Token',
        'plural_model_label' => 'Tokens',
        'column' => [
            'id' => 'ID',
            'name' => 'Name',
            'client' => 'Client',
            'user_name' => 'User Name',
            'scopes' => 'Scopes',
            'revoked' => 'Revoked',
            'created_at' => 'Created At',
            'expires_at' => 'Expires At',
        ],
    ]
];
