<?php

use Filament\Support\Icons\Heroicon;

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation Groups
    |--------------------------------------------------------------------------
    |
    | This values controls the navigation group name used by Filament
    | for all Passport-related resources.
    |
    */
    
    'navigation' => [
        'client_resource' => [
            'group' => 'filament-passport-ui::passport-ui.navigation.group',
            'icon' => Heroicon::OutlinedKey,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scope Management
    |--------------------------------------------------------------------------
    |
    | Whether the PassportScopeResource/PassportScopeAction management
    | resources are registered alongside ClientResource/TokenResource.
    |
    */

    'enable_scopes_management' => true,

    /*
    |--------------------------------------------------------------------------
    | Self-Service Scope Allow-List
    |--------------------------------------------------------------------------
    |
    | In self-service mode, which scopes a user may put on their own OAuth
    | client is an application concern (roles, permissions, plan, tenant), not
    | something this package can decide. Point this at a class implementing
    | N3XT0R\FilamentPassportUi\Contracts\SelfServiceScopeResolver to supply
    | the allow-list. Leave it null to let self-service users choose freely
    | from the configured scope taxonomy.
    |
    */

    'self_service_scope_resolver' => null,
];
