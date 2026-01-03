<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Providers\Boot;

use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\AuthorizationCodeClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ClientCredentialsClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\DeviceGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ImplicitGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PasswordGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PersonalAccessClientStrategy;

/**
 * Boots OAuth strategies by tagging them in the application container.
 */
class OAuthStrategyBooter extends BaseBooter
{
    public function boot(): void
    {
        $this->app->tag([
            PersonalAccessClientStrategy::class,
            PasswordGrantClientStrategy::class,
            ClientCredentialsClientStrategy::class,
            ImplicitGrantClientStrategy::class,
            AuthorizationCodeClientStrategy::class,
            DeviceGrantClientStrategy::class,
        ], 'filament-passport-ui.oauth.strategies');
    }
}
