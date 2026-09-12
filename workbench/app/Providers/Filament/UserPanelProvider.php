<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;

/**
 * Second workbench panel, running the plugin in self-service mode.
 *
 * It exists so the self-service variant can be exercised the way a consuming
 * application actually wires it, rather than by attaching a self-service plugin
 * to the admin panel at runtime. Deliberately bare: no resource discovery and
 * no widgets, so everything visible here comes from the plugin under test.
 */
class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->login()
            ->colors([
                // Blue reads as the account colour and sits far enough from the
                // admin panel's amber to tell the two apart at a glance.
                'primary' => Color::Blue,
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->pages([
                Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentPassportUiPlugin::make()->selfService(),
            ]);
    }
}
