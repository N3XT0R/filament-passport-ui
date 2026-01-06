<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Tests\Integration\Commands;

use Illuminate\Console\Command;
use N3XT0R\FilamentPassportUi\Models\Passport\Client as PassportClient;
use N3XT0R\FilamentPassportUi\Models\PassportScopeAction;
use N3XT0R\FilamentPassportUi\Models\PassportScopeResource;
use N3XT0R\FilamentPassportUi\Tests\DatabaseTestCase;

final class FilamentPassportUiCommandTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->disableObservers();
    }

    public function testCommandDisplaysCompletionMessage(): void
    {
        $this->artisan('filament-passport-ui')
            ->expectsOutput('All done')
            ->assertExitCode(Command::SUCCESS);
    }

    private function disableObservers(): void
    {
        PassportClient::flushEventListeners();
        PassportScopeResource::flushEventListeners();
        PassportScopeAction::flushEventListeners();
    }
}
