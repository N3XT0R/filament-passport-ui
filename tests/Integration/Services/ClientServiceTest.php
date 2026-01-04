<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Services;

use App\Models\User;
use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\DTO\Client\OAuthClientData;
use N3XT0R\FilamentPassportUi\Enum\OAuthClientType;
use N3XT0R\FilamentPassportUi\Exceptions\Domain\ClientAlreadyExists;
use N3XT0R\FilamentPassportUi\Services\ClientService;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class ClientServiceTest extends DatabaseTestCase
{
    protected ClientService $service;

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

        self::assertDatabaseHas($client->getTable(), [
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

        $this->assertDatabaseHas('activity_log', [
            'subject_id' => $client->getKey(),
            'subject_type' => Client::class,
        ]);
    }

}
