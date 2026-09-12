<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Support\Scopes;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use N3XT0R\FilamentPassportUi\Contracts\SelfServiceScopeResolver;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Support\Scopes\SelfServiceScopes;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

class SelfServiceScopesTest extends DatabaseTestCase
{
    public function testAdminModeIsNeverRestricted(): void
    {
        config()->set('passport-ui.self_service_scope_resolver', StubScopeResolver::class);

        $this->assertNull(SelfServiceScopes::allowedFor(User::factory()->create()));
    }

    public function testSelfServiceWithoutAResolverIsNotRestricted(): void
    {
        $this->enableSelfService();
        config()->set('passport-ui.self_service_scope_resolver', null);

        $this->assertNull(
            SelfServiceScopes::allowedFor(User::factory()->create()),
            'Without an application resolver a self-service user may choose from the full taxonomy.',
        );
    }

    public function testSelfServiceUsesTheApplicationResolver(): void
    {
        $this->enableSelfService();
        config()->set('passport-ui.self_service_scope_resolver', StubScopeResolver::class);

        $allowed = SelfServiceScopes::allowedFor(User::factory()->create());

        $this->assertNotNull($allowed);
        $this->assertSame(['orders:read'], $allowed->all());
    }

    private function enableSelfService(): void
    {
        $panel = Filament::getPanel('admin');
        $panel->plugin(FilamentPassportUiPlugin::make()->selfService());
        Filament::setCurrentPanel($panel);
    }
}

class StubScopeResolver implements SelfServiceScopeResolver
{
    public function allowedScopesFor(?Authenticatable $actor): ?Collection
    {
        return collect(['orders:read']);
    }
}
