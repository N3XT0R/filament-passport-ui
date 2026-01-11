<?php

// translations for N3XT0R/FilamentPassportUi
return [
    'navigation' => [
        'group' => 'API Management',
    ],
    'common' => [
        'id' => 'ID',
        'name' => 'Name',
        'description' => 'Beschreibung',
        'updated_at' => 'Aktualisiert am',
        'created_at' => 'Erstellt am',
        'expires_at' => 'Läuft ab am',
        'scopes' => 'Berechtigungen',
        'none' => 'Keine',
        'is_active' => 'Aktiv',
    ],
    'resource' => [
        'global_action' => 'globale Aktion',
    ],
    'client_resource' => [
        'label' => 'OAuth Clients',
        'model_label' => 'Client',
        'plural_model_label' => 'Clients',
        'form' => [
            'owner_hint' => 'Der Eigentümer dieses Clients. Wird verwendet, um den Client einem Benutzer zuzuordnen.',
            'secret_label' => 'Geheimer Schlüssel',
            'secret_description' => 'Dieses Feld ist nur einmalig sichtbar nach der Erstellung der Resource. Stellen Sie sicher, dass Sie es an einem sicheren Ort speichern.',
            'revoke_label' => 'Client widerrufen',
            'wizard' => [
                'steps' => [
                    'client' => [
                        'label' => 'Client Details',
                        'description' => 'Füllen Sie die Client-Details aus.',
                    ],
                    'user_permission' => [
                        'label' => 'Benutzerberechtigung',
                        'description' => 'Weisen Sie dem Benutzer Berechtigungen zu.',
                    ],
                ],
            ],
        ],
        'column' => [
            'name' => 'Name',
            'owner' => 'Eigentümer',
            'grant_type' => 'Grant-Typ',
            'last_login' => 'Letzte Anmeldung',
            'revoked' => 'Widerrufen',
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
            'is_global' => 'Global',
        ],
        'form' => [
            'name' => 'Aktion',
            'description' => 'Beschreibung',
            'is_active' => 'Aktiv',
            'resource_id' => 'Resource',
            'resource_id_helper_text' => 'Wählen Sie die Ressource, zu der diese Aktion gehört. Leer lassen, um sie zu einer globalen Aktion zu machen.',
        ],
        'header_action' => [
            'create' => 'Resource Aktion erstellen',
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
            'user_name' => 'Benutzer',
            'scopes' => 'Berechtigungen',
            'revoked' => 'Widerrufen',
            'created_at' => 'Erstellt am',
            'expires_at' => 'Läuft ab am',
        ],
    ],
];
