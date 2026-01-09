<?php


use N3XT0R\LaravelPassportAuthorizationCore\LaravelPassportAuthorizationCoreServiceProvider;
use Spatie\Activitylog\ActivitylogServiceProvider;

return [
    App\Providers\WorkbenchServiceProvider::class,
    \Laravel\Passport\PassportServiceProvider::class,
    ActivitylogServiceProvider::class,
    LaravelPassportAuthorizationCoreServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
