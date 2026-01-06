<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Repositories;

use App\Models\Token;
use Carbon\Carbon;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Repositories\ClientRepository;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ClientRepositoryTest extends DatabaseTestCase
{
    protected ClientRepository $clientRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientRepository = $this->app->make(ClientRepository::class);
    }

    public function testAllReturnsAllClients(): void
    {
        Client::factory()->count(3)->create();

        $clients = $this->clientRepository->all();

        self::assertCount(3, $clients);
        self::assertContainsOnlyInstancesOf(Client::class, $clients);
    }

    public function testFindByNameReturnsClient(): void
    {
        $client = Client::factory()->create([
            'name' => 'My Client',
        ]);

        $found = $this->clientRepository->findByName('My Client');

        self::assertNotNull($found);
        self::assertSame($client->getKey(), $found->getKey());
    }

    public function testFindByNameReturnsNullWhenNotFound(): void
    {
        $found = $this->clientRepository->findByName('Unknown');

        self::assertNull($found);
    }

    public function testCountReturnsNumberOfClients(): void
    {
        Client::factory()->count(2)->create();

        self::assertSame(2, $this->clientRepository->count());
    }

    public function testGetLastLoginAtForClientReturnsLatestTokenTimestamp(): void
    {
        $client = Client::factory()->create();

        Token::factory()->create([
            'client_id' => $client->getKey(),
            'updated_at' => Carbon::now()->subDay(),
        ]);

        $latest = Token::factory()->create([
            'client_id' => $client->getKey(),
            'updated_at' => Carbon::now(),
        ]);

        $lastLoginAt = $this->clientRepository->getLastLoginAtForClient($client);

        self::assertInstanceOf(Carbon::class, $lastLoginAt);
        self::assertTrue($lastLoginAt->equalTo($latest->updated_at));
    }

    public function testGetLastLoginAtForClientReturnsNullWhenNoTokens(): void
    {
        $client = Client::factory()->create();

        self::assertNull(
            $this->clientRepository->getLastLoginAtForClient($client)
        );
    }

    public function testUpdateUpdatesNameAndRedirectUris(): void
    {
        $client = Client::factory()->create([
            'name' => 'Old Name',
        ]);

        $result = $this->clientRepository->update(
            $client,
            'New Name',
            ['https://example.com/callback']
        );

        $client->refresh();

        self::assertTrue($result);
        self::assertSame('New Name', $client->name);

        if (array_key_exists('redirect_uris', $client->getAttributes())) {
            self::assertSame(
                ['https://example.com/callback'],
                $client->redirect_uris
            );
        } else {
            self::assertSame(
                'https://example.com/callback',
                $client->redirect
            );
        }
    }
}
