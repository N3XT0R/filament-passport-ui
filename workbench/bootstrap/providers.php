<?php


use Spatie\Activitylog\ActivitylogServiceProvider;

return [
    App\Providers\WorkbenchServiceProvider::class,
    \Laravel\Passport\PassportServiceProvider::class,
    ActivitylogServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
