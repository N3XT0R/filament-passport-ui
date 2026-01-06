<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Models\Passport\Client as PassportClient;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ActionRepository;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedActionRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\CachedResourceRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\ResourceRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ClearCacheCommandTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->disableObservers();
        $this->enableCachedRepositories();
    }

    public function testCommandClearsCacheAndSucceeds(): void
    {
        Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.resources',
        ])->put('resource-cache-key', 'resource-entry', 600);

        Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.actions',
        ])->put('action-cache-key', 'action-entry', 600);

        self::assertSame('resource-entry', Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.resources',
        ])->get('resource-cache-key'));

        self::assertSame('action-entry', Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.actions',
        ])->get('action-cache-key'));

        $this->artisan('filament-passport-ui:cleanup-cache')
            ->assertExitCode(Command::SUCCESS);

        self::assertNull(Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.resources',
        ])->get('resource-cache-key'));

        self::assertNull(Cache::tags([
            'passport',
            'passport.scopes',
            'passport.scopes.actions',
        ])->get('action-cache-key'));
    }

    private function disableObservers(): void
    {
        PassportClient::flushEventListeners();
        PassportScopeResource::flushEventListeners();
        PassportScopeAction::flushEventListeners();
    }

    private function enableCachedRepositories(): void
    {
        $resourceRepository = new CachedResourceRepositoryDecorator(new ResourceRepository());
        $actionRepository = new CachedActionRepositoryDecorator(new ActionRepository());

        $this->app->instance(ResourceRepositoryContract::class, $resourceRepository);
        $this->app->instance(ActionRepositoryContract::class, $actionRepository);
    }
}
