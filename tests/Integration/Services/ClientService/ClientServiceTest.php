<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Services\ClientService;

use App\Models\User;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Exceptions\Domain\ClientAlreadyExists;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Services\ClientService;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ClientServiceTest extends DatabaseTestCase
{
    private ClientService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ClientService::class);
    }

    public function testCreateClientForUserCreatesClient(): void
    {
        $owner = User::factory()->create();

        $data = new OAuthClientData(
            name: 'Test Client',
            redirectUris: ['https://example.com/callback'],
            owner: $owner,
        );

        $client = $this->service->createClientForUser(
            OAuthClientType::PERSONAL_ACCESS,
            $data
        );

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Test Client', $client->name);
        self::assertSame($owner->getKey(), $client->owner?->getKey());

        $this->assertDatabaseHas($client->getTable(), [
            'id' => $client->getKey(),
            'name' => 'Test Client',
        ]);
    }

    public function testCreateClientForUserThrowsWhenClientAlreadyExists(): void
    {
        Client::factory()->create([
            'name' => 'Duplicate Client',
        ]);

        $owner = User::factory()->create();

        $data = new OAuthClientData(
            name: 'Duplicate Client',
            owner: $owner,
        );

        $this->expectException(ClientAlreadyExists::class);

        $this->service->createClientForUser(
            OAuthClientType::PERSONAL_ACCESS,
            $data
        );
    }

    public function testCreateClientForUserLogsActivityWithActor(): void
    {
        $owner = User::factory()->create();
        $actor = User::factory()->create();

        $data = new OAuthClientData(
            name: 'Actor Client',
            redirectUris: [],
            owner: $owner,
        );

        $client = $this->service->createClientForUser(
            OAuthClientType::PERSONAL_ACCESS,
            $data,
            $actor
        );

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth',
            'causer_id' => $actor->getKey(),
            'causer_type' => $actor::class,
            'description' => 'OAuth client created',
        ]);

        $this->assertDatabaseHas($client->getTable(), [
            'id' => $client->getKey(),
        ]);
    }

    public function testUpdateClientUpdatesProvidedFields(): void
    {
        $client = Client::factory()->create([
            'name' => 'Old Name',
            'redirect_uris' => ['https://old.example'],
            'revoked' => false,
        ]);

        $newOwner = User::factory()->create();

        $data = new OAuthClientData(
            name: 'New Name',
            redirectUris: ['https://new.example'],
            revoked: true,
            owner: $newOwner,
        );

        $updated = $this->service->updateClient($client, $data);

        self::assertSame('New Name', $updated->name);
        self::assertSame(['https://new.example'], $updated->redirect_uris);
        self::assertTrue($updated->revoked);
        self::assertSame($newOwner->getKey(), $updated->owner?->getKey());
    }

    public function testUpdateClientKeepsExistingValuesWhenDataIsEmpty(): void
    {
        $client = Client::factory()->create([
            'name' => 'Original Name',
            'redirect_uris' => ['https://original.example'],
            'revoked' => false,
        ]);

        $data = new OAuthClientData(
            name: '',
            redirectUris: [],
        );

        $updated = $this->service->updateClient($client, $data);

        self::assertSame('Original Name', $updated->name);
        self::assertSame(['https://original.example'], $updated->redirect_uris);
        self::assertFalse($updated->revoked);
    }

    public function testUpdateClientLogsActivityWithActor(): void
    {
        $actor = User::factory()->create();

        $client = Client::factory()->create([
            'name' => 'Before',
            'revoked' => false,
        ]);

        $data = new OAuthClientData(
            name: 'After',
            revoked: true,
        );

        $this->service->updateClient(
            $client,
            $data,
            $actor
        );

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth',
            'causer_id' => $actor->getKey(),
            'causer_type' => $actor::class,
            'description' => 'OAuth client updated',
        ]);
    }

    public function testChangeOwnerOfClient(): void
    {
        $client = Client::factory()->create();
        $newOwner = User::factory()->create();

        $updated = $this->service->changeOwnerOfClient(
            $client,
            $newOwner
        );

        self::assertSame(
            $newOwner->getAuthIdentifier(),
            $updated->owner?->getAuthIdentifier()
        );
    }

    public function testChangeOwnerOfClientLogsActivityWithActor(): void
    {
        $actor = User::factory()->create();
        $newOwner = User::factory()->create();

        $client = Client::factory()->create();

        $this->service->changeOwnerOfClient(
            $client,
            $newOwner,
            $actor
        );

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth',
            'causer_id' => $actor->getKey(),
            'causer_type' => $actor::class,
            'description' => 'OAuth client ownership changed',
        ]);
    }

    public function testGetOwnerLabelAttributeReturnsConfiguredLabel(): void
    {
        config([
            'passport-ui.owner_label_attribute' => 'name',
        ]);

        $owner = User::factory()->create([
            'name' => 'test',
        ]);

        $client = Client::factory()->create();
        $client->owner()->associate($owner);
        $client->save();

        self::assertSame(
            'test',
            $this->service->getOwnerLabelAttribute($client)
        );
    }

    public function testGetOwnerLabelAttributeReturnsLabelUsingClientId(): void
    {
        config([
            'passport-ui.owner_label_attribute' => 'name',
        ]);

        $owner = User::factory()->create([
            'name' => 'from id',
        ]);

        $client = Client::factory()->create();
        $client->owner()->associate($owner);
        $client->save();

        self::assertSame(
            'from id',
            $this->service->getOwnerLabelAttribute($client->getKey())
        );
    }

    public function testGetOwnerLabelAttributeReturnsNullWhenClientNotFound(): void
    {
        self::assertNull(
            $this->service->getOwnerLabelAttribute(999999)
        );
    }

    public function testGetOwnerLabelAttributeReturnsNullWhenNoOwner(): void
    {
        $client = Client::factory()->create();

        self::assertNull(
            $this->service->getOwnerLabelAttribute($client)
        );
    }

    public function testGetOwnerLabelAttributeReturnsNullWhenConfiguredLabelMissing(): void
    {
        config([
            'passport-ui.owner_label_attribute' => 'non_existing_property',
        ]);

        $owner = User::factory()->create([
            'name' => 'unused label',
        ]);

        $client = Client::factory()->create();
        $client->owner()->associate($owner);
        $client->save();

        self::assertNull(
            $this->service->getOwnerLabelAttribute($client)
        );
    }

    public function testDeleteClientRemovesClientWithoutActor(): void
    {
        $client = Client::factory()->create([
            'name' => 'Delete Me',
        ]);

        $result = $this->service->deleteClient($client);

        self::assertTrue($result);

        $this->assertDatabaseMissing($client->getTable(), [
            'id' => $client->getKey(),
        ]);

        $this->assertDatabaseCount('activity_log', 0);
    }

    public function testDeleteClientLogsActivityWithActor(): void
    {
        $actor = User::factory()->create();
        $client = Client::factory()->create([
            'name' => 'Delete Me Too',
        ]);

        $result = $this->service->deleteClient($client, $actor);

        self::assertTrue($result);

        $this->assertDatabaseMissing($client->getTable(), [
            'id' => $client->getKey(),
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'oauth',
            'causer_id' => $actor->getKey(),
            'causer_type' => $actor::class,
            'description' => 'OAuth client deleted',
        ]);
    }
}
