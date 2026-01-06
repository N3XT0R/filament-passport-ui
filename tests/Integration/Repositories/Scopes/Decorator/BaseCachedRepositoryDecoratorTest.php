<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories\Scopes\Decorator;

use Illuminate\Support\Facades\Cache;
use N3XT0R\FilamentPassportUi\Repositories\Scopes\Decorator\BaseCachedRepositoryDecorator;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class BaseCachedRepositoryDecoratorTest extends DatabaseTestCase
{
    private TestCachedRepositoryDecorator $decorator;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->decorator = new TestCachedRepositoryDecorator();
    }

    public function testClearCacheFlushesTaggedEntries(): void
    {
        $this->decorator->cacheValue('example', 'cached-value');

        self::assertSame('cached-value', $this->decorator->getCached('example'));

        $this->decorator->clearCache();

        self::assertNull($this->decorator->getCached('example'));
    }

    public function testTtlUsesPassportUiConfiguration(): void
    {
        config(['passport-ui.cache.ttl' => 120]);

        self::assertSame(120, $this->decorator->ttlSeconds());
    }
}

final class TestCachedRepositoryDecorator extends BaseCachedRepositoryDecorator
{
    protected const array CACHE_TAGS = [
        'passport',
        'passport.scopes',
        'passport.scopes.actions',
    ];

    public function cacheValue(string $key, mixed $value): void
    {
        Cache::tags(static::CACHE_TAGS)->put($key, $value, $this->ttl());
    }

    public function getCached(string $key): mixed
    {
        return Cache::tags(static::CACHE_TAGS)->get($key);
    }

    public function ttlSeconds(): int
    {
        return $this->ttl();
    }
}
