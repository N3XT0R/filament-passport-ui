<?php

declare(strict_types=1);

namespace N3XT0R\FilamentPassportUi\Database\Seeders;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

final class PassportScopeActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = [
            [
                'name' => 'list',
                'description' => 'List resources',
            ],
            [
                'name' => 'read',
                'description' => 'Read resource details',
            ],
            [
                'name' => 'create',
                'description' => 'Create new resources',
            ],
            [
                'name' => 'update',
                'description' => 'Update existing resources',
            ],
            [
                'name' => 'delete',
                'description' => 'Delete resources',
            ],
        ];

        $date = Carbon::now();

        foreach ($actions as $action) {
            $this->createAction($action['name'], $action['description'], $date);
        }
    }

    protected function createAction(string $name, string $description, ?CarbonInterface $date = null): void
    {
        DB::table('passport_scope_actions')->updateOrInsert(
            ['name' => $name],
            [
                'description' => $description,
                'updated_at' => $date,
                'created_at' => $date,
            ]
        );
    }
}
