<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Support\Cache;

use Illuminate\Support\Facades\Cache;

final class CacheFlasher
{
    private const string PREFIX = 'flash:';
    private const int DEFAULT_TTL_MINUTES = 2;

    public static function put(
        string $scope,
        string|int $key,
        mixed $value,
        ?int $ttlMinutes = null,
    ): void {
        Cache::put(
            self::cacheKey($scope, $key),
            encrypt($value),
            now()->addMinutes($ttlMinutes ?? self::DEFAULT_TTL_MINUTES),
        );
    }

    public static function pull(
        string $scope,
        string|int $key,
    ): mixed {
        $value = Cache::pull(self::cacheKey($scope, $key));

        return $value !== null
            ? decrypt($value)
            : null;
    }

    private static function cacheKey(string $scope, string|int $key): string
    {
        return self::PREFIX . $scope . ':' . $key;
    }
}