<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Factories\OAuth\Strategy;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\PasswordGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class PasswordGrantClientStrategyTest extends DatabaseTestCase
{
    public function testCreatePasswordGrantClient(): void
    {
        $strategy = $this->app->make(PasswordGrantClientStrategy::class);

        $client = $strategy->create('Password Grant App', [], 'users');

        self::assertTrue($strategy->supports(OAuthClientType::PASSWORD));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Password Grant App', $client->name);
        self::assertSame('users', $client->provider);
        self::assertTrue(in_array('password', $client->grant_types, true));
        self::assertTrue(in_array('refresh_token', $client->grant_types, true));
        self::assertNotNull($client->plainSecret);
    }
}
