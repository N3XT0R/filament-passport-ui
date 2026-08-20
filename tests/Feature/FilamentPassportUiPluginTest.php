<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature;

use Filament\Panel;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Resources\ClientResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeActionsResource;
use N3XT0R\FilamentPassportUi\Resources\PassportScopeResourceResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class FilamentPassportUiPluginTest extends DatabaseTestCase
{
    public function testScopeManagementResourcesAreExcludedWhenDisabledViaConfig(): void
    {
        config(['passport-ui.enable_scopes_management' => false]);

        $panel = Panel::make()->id('test-scopes-disabled');
        FilamentPassportUiPlugin::make()->register($panel);

        $this->assertNotContains(PassportScopeResourceResource::class, $panel->getResources());
        $this->assertNotContains(PassportScopeActionsResource::class, $panel->getResources());
        $this->assertContains(ClientResource::class, $panel->getResources());
    }

    public function testScopeManagementResourcesAreIncludedByDefault(): void
    {
        $panel = Panel::make()->id('test-scopes-enabled');
        FilamentPassportUiPlugin::make()->register($panel);

        $this->assertContains(PassportScopeResourceResource::class, $panel->getResources());
        $this->assertContains(PassportScopeActionsResource::class, $panel->getResources());
    }
}
