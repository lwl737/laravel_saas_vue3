<?php

declare(strict_types=1);

namespace Database\Seeders\DevMenu;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuTableSeeder::class,
            MenuRelationTableSeeder::class,
            MenuAuthTableSeeder::class,
            MenuAuthApiTableSeeder::class,
            MenuAuthButtonsTableSeeder::class,
        ]);
    }
}
