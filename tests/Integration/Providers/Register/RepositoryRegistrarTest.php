<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Providers\Register;

use Laravel\Passport\ClientRepository as BaseClientRepository;
use N3XT0R\FilamentPassportUi\Providers\Register\RepositoryRegistrar;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Repositories\ConfigRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Tests\TestCase;

final class RepositoryRegistrarTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [];
    }

    public function testItRegistersClientRepository(): void
    {
        $this->app->make(RepositoryRegistrar::class)->register();

        $repository = $this->app->make(BaseClientRepository::class);

        self::assertInstanceOf(ClientRepository::class, $repository);
    }

    public function testItRegistersConfigRepository(): void
    {
        $this->app->make(RepositoryRegistrar::class)->register();

        $repository = $this->app->make(ConfigRepository::class);

        self::assertInstanceOf(ConfigRepository::class, $repository);
    }

    public function testItRegistersActionRepositoryWithoutDecoratorInTests(): void
    {
        $this->app->make(RepositoryRegistrar::class)->register();

        $repository = $this->app->make(ActionRepositoryContract::class);

        self::assertInstanceOf(ActionRepository::class, $repository);
    }

    public function testItRegistersResourceRepositoryWithoutDecoratorInTests(): void
    {
        $this->app->make(RepositoryRegistrar::class)->register();

        $repository = $this->app->make(ResourceRepositoryContract::class);

        self::assertInstanceOf(ResourceRepository::class, $repository);
    }

    public function testRepositoriesAreSingletons(): void
    {
        $this->app->make(RepositoryRegistrar::class)->register();

        self::assertSame(
            $this->app->make(ActionRepositoryContract::class),
            $this->app->make(ActionRepositoryContract::class)
        );

        self::assertSame(
            $this->app->make(ResourceRepositoryContract::class),
            $this->app->make(ResourceRepositoryContract::class)
        );
    }
}
