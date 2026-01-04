<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Factories\OAuth\Strategy;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\DeviceGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class DeviceGrantClientStrategyTest extends DatabaseTestCase
{
    public function testCreateDeviceGrantClientForUser(): void
    {
        $strategy = $this->app->make(DeviceGrantClientStrategy::class);
        $user = User::factory()->create();

        $client = $strategy->create('Device Grant App', [], null, false, $user);

        self::assertTrue($strategy->supports(OAuthClientType::DEVICE));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Device Grant App', $client->name);
        self::assertSame($user->getKey(), $client->owner?->getKey());
        self::assertTrue(in_array('urn:ietf:params:oauth:grant-type:device_code', $client->grant_types, true));
        self::assertTrue(in_array('refresh_token', $client->grant_types, true));
        self::assertNull($client->secret);
    }
}
