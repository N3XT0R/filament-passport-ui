<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories;

use Filament\Support\Icons\Heroicon;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;

final class ConfigRepositoryTest extends DatabaseTestCase
{
    protected ConfigRepository $configRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configRepository = $this->app->make(ConfigRepository::class);
    }

    public function testGetAllowedGrantTypesReturnsEnumInstances(): void
    {
        config([
            'passport-authorization-core.oauth.allowed_grant_types' => [
                OAuthClientType::PASSWORD->value,
                OAuthClientType::CLIENT_CREDENTIALS->value,
            ],
        ]);

        $types = $this->configRepository->getAllowedGrantTypes();

        self::assertSame(
            [
                OAuthClientType::PASSWORD,
                OAuthClientType::CLIENT_CREDENTIALS,
            ],
            $types
        );
    }

    public function testGetAllowedGrantTypesReturnsEmptyArrayWhenNotConfigured(): void
    {
        config(['passport-authorization-core.oauth.allowed_grant_types' => []]);

        self::assertSame(
            [],
            $this->configRepository->getAllowedGrantTypes()
        );
    }

    public function testGetOwnerModelReturnsConfiguredValue(): void
    {
        config([
            'passport-authorization-core.owner_model' => '\\App\\Models\\Admin',
        ]);

        self::assertSame(
            '\\App\\Models\\Admin',
            $this->configRepository->getOwnerModel()
        );
    }

    public function testGetOwnerModelReturnsDefaultWhenNotConfigured(): void
    {
        self::assertSame(
            '\\App\\Models\\User',
            $this->configRepository->getOwnerModel()
        );
    }

    public function testGetOwnerLabelAttribute(): void
    {
        config([
            'passport-authorization-core.owner_label_attribute' => 'email',
        ]);

        self::assertSame(
            'email',
            $this->configRepository->getOwnerLabelAttribute()
        );
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

    public function testIsUsingDatabaseScopesReturnsTrueWhenEnabled(): void
    {
        config([
            'passport-authorization-core.use_database_scopes' => true,
        ]);

        self::assertTrue(
            $this->configRepository->isUsingDatabaseScopes()
        );
    }

    public function testIsUsingDatabaseScopesReturnsFalseWhenDisabled(): void
    {
        config([
            'passport-authorization-core.use_database_scopes' => false,
        ]);

        self::assertFalse(
            $this->configRepository->isUsingDatabaseScopes()
        );
    }
}
