<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PassportScopeResourceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $resources = [
            [
                'name' => 'users',
                'description' => 'User accounts and identities',
            ],
        ];

        foreach ($resources as $resource) {
            DB::table('passport_scope_resources')->updateOrInsert(
                ['name' => $resource['name']],
                [
                    'description' => $resource['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
