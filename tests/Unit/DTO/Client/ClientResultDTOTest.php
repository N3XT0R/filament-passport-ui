<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Unit\DTO\Client;

use Laravel\Passport\Client;
use N3XT0R\FilamentPassportUi\DTO\Client\ClientResultDTO;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ClientResultDTOTest extends TestCase
{
    public function testItCreatesDtoWithClientAndPlainSecret(): void
    {
        $client = new Client();
        $plainSecret = 'plain-secret-value';

        $dto = new ClientResultDTO(
            client: $client,
            plainSecret: $plainSecret,
        );

        self::assertSame($client, $dto->client);
        self::assertSame($plainSecret, $dto->plainSecret);
    }

    public function testItCreatesDtoWithNullPlainSecret(): void
    {
        $client = new Client();

        $dto = new ClientResultDTO(
            client: $client,
        );

        self::assertSame($client, $dto->client);
        self::assertNull($dto->plainSecret);
    }

    public function testDtoIsReadonly(): void
    {
        $reflection = new ReflectionClass(ClientResultDTO::class);

        self::assertTrue(
            $reflection->isReadOnly(),
            'ClientResultDTO must be declared as readonly.'
        );
    }

    public function testClientPropertyHasCorrectType(): void
    {
        $reflection = new ReflectionClass(ClientResultDTO::class);
        $property = $reflection->getProperty('client');

        self::assertSame(
            Client::class,
            $property->getType()?->getName()
        );
    }

    public function testPlainSecretPropertyAllowsNull(): void
    {
        $reflection = new ReflectionClass(ClientResultDTO::class);
        $property = $reflection->getProperty('plainSecret');

        self::assertTrue(
            $property->getType()?->allowsNull()
        );
    }
}
