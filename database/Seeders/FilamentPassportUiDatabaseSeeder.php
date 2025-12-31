<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Seeders;

use Illuminate\Database\Seeder;

final class FilamentPassportUiDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PassportScopeResourceSeeder::class,
            PassportScopeActionSeeder::class,
        ]);
    }
}
