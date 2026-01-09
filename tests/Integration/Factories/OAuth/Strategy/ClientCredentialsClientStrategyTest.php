<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Factories\OAuth\Strategy;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ClientCredentialsClientStrategy;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class ClientCredentialsClientStrategyTest extends DatabaseTestCase
{
    public function testCreateClientCredentialsClient(): void
    {
        $strategy = $this->app->make(ClientCredentialsClientStrategy::class);

        $client = $strategy->create('Client Credentials App');

        self::assertTrue($strategy->supports(OAuthClientType::CLIENT_CREDENTIALS));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Client Credentials App', $client->name);
        self::assertTrue(in_array('client_credentials', $client->grant_types, true));
        self::assertNotNull($client->plainSecret);
        self::assertEmpty($client->redirect_uris);
    }
}
