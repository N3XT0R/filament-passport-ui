<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\StateResolvers\ScopeAction;

use N3XT0R\FilamentPassportUi\Application\StateResolvers\ScopeAction\FormatIsGlobalState;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

final class FormatIsGlobalStateTest extends DatabaseTestCase
{
    private FormatIsGlobalState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(FormatIsGlobalState::class);
    }

    public function testExecuteReturnsTrueWhenActionHasNoResource(): void
    {
        $action = PassportScopeAction::factory()->create([
            'resource_id' => null,
        ]);

        $result = $this->stateResolver->execute($action);

        self::assertTrue($result);
    }

    public function testExecuteReturnsFalseWhenActionIsLinkedToResource(): void
    {
        $resource = PassportScopeResource::factory()->create();
        $action = PassportScopeAction::factory()->create([
            'resource_id' => $resource->getKey(),
        ]);

        $result = $this->stateResolver->execute($action);

        self::assertFalse($result);
    }
}
