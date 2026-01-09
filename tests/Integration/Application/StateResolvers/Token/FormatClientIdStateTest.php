<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\StateResolvers\Token;

use N3XT0R\FilamentPassportUi\Application\StateResolvers\Token\FormatClientIdState;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class FormatClientIdStateTest extends DatabaseTestCase
{
    private FormatClientIdState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(FormatClientIdState::class);
    }

    public function testExecuteReturnsClientNameWhenClientExists(): void
    {
        $client = Client::factory()->create([
            'name' => 'Test Client',
        ]);

        $result = $this->stateResolver->execute((string)$client->getKey());

        self::assertSame('Test Client', $result);
    }

    public function testExecuteReturnsNullWhenClientDoesNotExist(): void
    {
        $result = $this->stateResolver->execute('999999');

        self::assertNull($result);
    }
}
