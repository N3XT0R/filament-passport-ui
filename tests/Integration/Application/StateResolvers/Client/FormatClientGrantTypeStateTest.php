<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\StateResolvers\Client;

use N3XT0R\FilamentPassportUi\Application\StateResolvers\Client\FormatClientGrantTypeState;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

final class FormatClientGrantTypeStateTest extends DatabaseTestCase
{
    private FormatClientGrantTypeState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(FormatClientGrantTypeState::class);
    }

    public function testExecuteReturnsFirstGrantTypeFromRecord(): void
    {
        $client = Client::factory()->create([
            'grant_types' => ['authorization_code', 'refresh_token'],
        ]);

        $result = $this->stateResolver->execute(null, $client);

        self::assertSame('authorization_code', $result);
    }

    public function testExecuteReturnsGivenStateWhenNoRecordProvided(): void
    {
        $result = $this->stateResolver->execute('password', null);

        self::assertSame('password', $result);
    }
}
