<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\StateResolvers\Client;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Client\FormatOwnerState;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class FormatOwnerStateTest extends DatabaseTestCase
{
    private FormatOwnerState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(FormatOwnerState::class);
    }

    public function testExecuteReturnsOwnerKeyWhenRecordExists(): void
    {
        $owner = User::factory()->create();

        $client = Client::factory()->create();
        $client->owner()->associate($owner);
        $client->save();

        $result = $this->stateResolver->execute(null, $client);

        self::assertSame($owner->getKey(), $result);
    }

    public function testExecuteReturnsStateWhenRecordIsNull(): void
    {
        $result = $this->stateResolver->execute('fallback', null);

        self::assertSame('fallback', $result);
    }
}
