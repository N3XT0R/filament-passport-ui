<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Factories;

use App\Models\User;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Exceptions\UnsupportedOAuthClientTypeException;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactory;
use N3XT0R\FilamentPassportUi\Factories\OAuth\OAuthClientFactoryInterface;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class OAuthClientFactoryTest extends DatabaseTestCase
{
    protected OAuthClientFactoryInterface $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = $this->app->make(OAuthClientFactoryInterface::class);
    }

    public function testCreatesPersonalAccessClient(): void
    {
        $user = User::factory()->create();

        $data = new OAuthClientData(
            name: 'Personal Access Client',
            owner: $user,
        );

        $client = ($this->factory)(
            OAuthClientType::PERSONAL_ACCESS,
            $data,
            $user
        );

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Personal Access Client', $client->name);

        $this->assertDatabaseHas($client->getTable(), [
            'id' => $client->getKey(),
            'name' => 'Personal Access Client',
        ]);
    }

    public function testCreatesPasswordGrantClient(): void
    {
        $user = User::factory()->create();

        $data = new OAuthClientData(
            name: 'Password Client',
            confidential: false,
            owner: $user,
        );

        $client = ($this->factory)(
            OAuthClientType::PASSWORD,
            $data,
            $user
        );

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Password Client', $client->name);
    }

    public function testCreatesClientCredentialsClient(): void
    {
        $data = new OAuthClientData(
            name: 'Client Credentials Client',
        );

        $client = ($this->factory)(
            OAuthClientType::CLIENT_CREDENTIALS,
            $data
        );

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Client Credentials Client', $client->name);
    }

    public function testCreatesImplicitClient(): void
    {
        $data = new OAuthClientData(
            name: 'Implicit Client',
            redirectUris: ['https://example.com/callback'],
            confidential: false,
        );

        $client = ($this->factory)(
            OAuthClientType::IMPLICIT,
            $data
        );

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Implicit Client', $client->name);
    }

    public function testCreatesAuthorizationCodeClient(): void
    {
        $user = User::factory()->create();

        $data = new OAuthClientData(
            name: 'Authorization Code Client',
            redirectUris: ['https://example.com/callback'],
            owner: $user,
        );

        $client = ($this->factory)(
            OAuthClientType::AUTHORIZATION_CODE,
            $data,
            $user
        );

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Authorization Code Client', $client->name);
    }

    public function testThrowsExceptionForUnsupportedType(): void
    {
        $factory = new OAuthClientFactory([]);

        $data = new OAuthClientData(
            name: 'Invalid Client',
        );

        $this->expectException(UnsupportedOAuthClientTypeException::class);

        $factory(
            OAuthClientType::DEVICE,
            $data
        );
    }
}
