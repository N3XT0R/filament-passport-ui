<?php


return [
    Workbench\App\Providers\WorkbenchServiceProvider::class,
    \Laravel\Passport\PassportServiceProvider::class,
    Workbench\App\Providers\Filament\AdminPanelProvider::class,
    \N3XT0R\FilamentPassportUi\Providers\PassportServiceProvider::class,
];
