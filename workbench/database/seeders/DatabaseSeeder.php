<?php

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // UserFactory::new()->times(10)->create();

        $factory = UserFactory::new();
        if (app()->runningInConsole()) {
            $factory->withPassword('password');
        }

        $factory->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
