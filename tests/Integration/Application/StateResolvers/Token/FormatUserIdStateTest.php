<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\StateResolvers\Token;

use App\Models\Token;
use App\Models\User;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Token\FormatUserIdState;
use N3XT0R\FilamentPassportUi\Models\Passport\Client;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class FormatUserIdStateTest extends DatabaseTestCase
{
    private FormatUserIdState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(FormatUserIdState::class);
    }

    public function testExecuteReturnsOwnerLabelForClient(): void
    {
        $owner = User::factory()->create([
            'name' => 'Owner Name',
        ]);

        $client = Client::factory()->create();
        $client->owner()->associate($owner);
        $client->save();

        $token = Token::factory()->create([
            'client_id' => $client->getKey(),
        ]);

        $result = $this->stateResolver->execute($token);

        self::assertSame('Owner Name', $result);
    }

    public function testExecuteReturnsNullWhenClientHasNoOwner(): void
    {
        $client = Client::factory()->create();
        $token = Token::factory()->create([
            'client_id' => $client->getKey(),
        ]);

        $result = $this->stateResolver->execute($token);

        self::assertNull($result);
    }
}
