<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\StateResolvers\Token;

use App\Models\User;
use N3XT0R\FilamentPassportUi\Application\StateResolvers\Token\GetOwnerState;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class GetOwnerStateTest extends DatabaseTestCase
{
    private GetOwnerState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(GetOwnerState::class);
    }

    public function testExecuteReturnsOwnerWhenOwnerExists(): void
    {
        $owner = User::factory()->create();

        $result = $this->stateResolver->execute($owner->getKey());

        self::assertNotNull($result);
        self::assertSame($owner->getKey(), $result->getKey());
        self::assertInstanceOf(User::class, $result);
    }

    public function testExecuteReturnsNullWhenOwnerIdIsNull(): void
    {
        $result = $this->stateResolver->execute(null);

        self::assertNull($result);
    }

    public function testExecuteReturnsNullWhenOwnerDoesNotExist(): void
    {
        $result = $this->stateResolver->execute(999999);

        self::assertNull($result);
    }
}
