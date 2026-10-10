<?php

declare(strict_types=1);

namespace Database\Seeders\DevMysql;


class DatabaseSeeder extends Base
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminsTableSeeder::class,
            CityTableSeeder::class
        ]);
    }
}
