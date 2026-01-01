<?php


return [
    App\Providers\WorkbenchServiceProvider::class,
    \Laravel\Passport\PassportServiceProvider::class,
    \Filament\FilamentServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    \N3XT0R\FilamentPassportUi\Providers\PassportServiceProvider::class,
];
