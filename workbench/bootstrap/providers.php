<?php

use Laravel\Passport\PassportServiceProvider;

return [
    Workbench\App\Providers\WorkbenchServiceProvider::class,
    PassportServiceProvider::class,
    Workbench\App\Providers\Filament\AdminPanelProvider::class,
];
