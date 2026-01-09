<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories;

use Filament\Support\Icons\Heroicon;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ConfigRepositoryTest extends DatabaseTestCase
{
    protected ConfigRepository $configRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configRepository = $this->app->make(ConfigRepository::class);
    }

    public function testGetNavigationGroupReturnsConfiguredValue(): void
    {
        config([
            'passport-ui.navigation.group' => 'Security',
        ]);

        self::assertSame(
            'Security',
            $this->configRepository->getNavigationGroup()
        );
    }

    public function testGetNavigationGroupReturnsDefault(): void
    {
        self::assertSame(
            'OAuth Management',
            $this->configRepository->getNavigationGroup()
        );
    }

    public function testGetNavigationIconReturnsConfiguredIcon(): void
    {
        config([
            'passport-ui.navigation.clients.icon' => Heroicon::OutlinedUser,
        ]);

        self::assertSame(
            Heroicon::OutlinedUser,
            $this->configRepository->getNavigationIcon('clients')
        );
    }

    public function testGetNavigationIconReturnsDefaultIcon(): void
    {
        self::assertSame(
            Heroicon::OutlinedKey,
            $this->configRepository->getNavigationIcon('clients')
        );
    }
}
