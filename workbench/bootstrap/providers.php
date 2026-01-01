<?php


return [
    Workbench\App\Providers\WorkbenchServiceProvider::class,
    \Laravel\Passport\PassportServiceProvider::class,
    \Filament\FilamentServiceProvider::class,
    Workbench\App\Providers\Filament\AdminPanelProvider::class,
    \N3XT0R\FilamentPassportUi\Providers\PassportServiceProvider::class,
];
