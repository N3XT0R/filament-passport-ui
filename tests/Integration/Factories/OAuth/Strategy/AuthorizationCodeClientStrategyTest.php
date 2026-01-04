<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Factories\OAuth\Strategy;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\AuthorizationCodeClientStrategy;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class AuthorizationCodeClientStrategyTest extends DatabaseTestCase
{
    public function testCreateAuthorizationCodeClientWithOptionalDeviceFlow(): void
    {
        $strategy = $this->app->make(AuthorizationCodeClientStrategy::class);
        $user = User::factory()->create();

        $client = $strategy->create(
            'Authorization Code Client',
            ['https://example.com/callback'],
            null,
            true,
            $user,
            ['enable_device_flow' => true]
        );

        self::assertTrue($strategy->supports(OAuthClientType::AUTHORIZATION_CODE));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Authorization Code Client', $client->name);
        self::assertSame(['https://example.com/callback'], $client->redirect_uris);
        self::assertTrue(in_array('authorization_code', $client->grant_types, true));
        self::assertTrue(in_array('refresh_token', $client->grant_types, true));
        self::assertTrue(in_array('urn:ietf:params:oauth:grant-type:device_code', $client->grant_types, true));
        self::assertSame($user->getKey(), $client->owner?->getKey());
    }
}
