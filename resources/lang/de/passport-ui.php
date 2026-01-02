<?php

// translations for N3XT0R/FilamentPassportUi
return [
    'navigation' => [
        'group' => 'API Management',
    ],
    'common' => [
        'updated_at' => 'Aktualisiert am',
        'created_at' => 'Erstellt am',
        'scopes' => 'Berechtigungen',
    ],
    'resource' => [
        'global_action' => 'globale Aktion',
    ],
    'client_resource' => [
        'label' => 'OAuth Clients',
        'model_label' => 'Client',
        'plural_model_label' => 'Clients',
        'column' => [
            'name' => 'Name',
            'owner' => 'Eigentümer',
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
            'description' => 'Beschreibung',
            'is_active' => 'Aktiv',
        ],
        'form' => [
            'name' => 'Name',
            'description' => 'Beschreibung',
            'is_active' => 'Aktiv',
        ],
    ],
    'passport_scope_actions_resource' => [
        'label' => 'Resource Aktionen',
        'model_label' => 'Resource Aktion',
        'plural_model_label' => 'Resource Aktionen',
        'column' => [
            'id' => 'ID',
            'name' => 'Aktion',
            'description' => 'Beschreibung',
            'is_active' => 'Aktiv',
            'is_global' => ''
        ],
        'form' => [
            'name' => 'Action',
            'description' => 'Beschreibung',
            'is_active' => 'Aktiv',
        ],
        'header_action' => [
            'create' => 'Resource Aktion erstellen',
        ],
    ],
];
