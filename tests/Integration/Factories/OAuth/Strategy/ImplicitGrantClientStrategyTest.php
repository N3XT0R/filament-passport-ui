<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Factories\OAuth\Strategy;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Factories\OAuth\Strategy\ImplicitGrantClientStrategy;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class ImplicitGrantClientStrategyTest extends DatabaseTestCase
{
    public function testCreateImplicitGrantClient(): void
    {
        $strategy = $this->app->make(ImplicitGrantClientStrategy::class);

        $client = $strategy->create('Implicit Client', ['https://example.com/implicit']);

        self::assertTrue($strategy->supports(OAuthClientType::IMPLICIT));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Implicit Client', $client->name);
        self::assertSame(['https://example.com/implicit'], $client->redirect_uris);
        self::assertTrue(in_array('implicit', $client->grant_types, true));
        self::assertNull($client->secret);
    }
}
