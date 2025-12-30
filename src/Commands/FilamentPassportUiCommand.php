<?php

namespace N3XT0R\FilamentPassportUi\Commands;

use Illuminate\Console\Command;

class FilamentPassportUiCommand extends Command
{
    public $signature = 'filament-passport-ui';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
