<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Feature\Panels;

use Filament\Facades\Filament;
use N3XT0R\FilamentPassportUi\FilamentPassportUiPlugin;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

/**
 * The workbench ships a second panel so the self-service variant can be
 * exercised for real, both by clicking through it and from tests, instead of
 * bolting a self-service plugin onto the admin panel at runtime.
 */
class UserPanelTest extends DatabaseTestCase
{
    public function testTheWorkbenchProvidesAUserPanelInSelfServiceMode(): void
    {
        $panel = Filament::getPanel('user');

        $this->assertSame('user', $panel->getId());
        $this->assertSame('user', $panel->getPath());

        Filament::setCurrentPanel($panel);

        $this->assertTrue(FilamentPassportUiPlugin::get()->isSelfService());
    }

    public function testTheAdminPanelStaysTheDefaultAndIsNotSelfService(): void
    {
        $admin = Filament::getPanel('admin');

        $this->assertTrue($admin->isDefault());

        Filament::setCurrentPanel($admin);

        $this->assertFalse(FilamentPassportUiPlugin::get()->isSelfService());
    }
}
