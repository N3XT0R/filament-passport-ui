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
];
