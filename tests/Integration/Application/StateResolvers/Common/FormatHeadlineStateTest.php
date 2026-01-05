<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Application\StateResolvers\Common;

use N3XT0R\FilamentPassportUi\Application\StateResolvers\Common\FormatHeadlineState;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class FormatHeadlineStateTest extends DatabaseTestCase
{
    private FormatHeadlineState $stateResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateResolver = $this->app->make(FormatHeadlineState::class);
    }

    public function testExecuteFormatsStateWithHeadline(): void
    {
        $result = $this->stateResolver->execute('api_scope_action');

        self::assertSame('Api Scope Action', $result);
    }
}
