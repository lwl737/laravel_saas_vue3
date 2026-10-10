<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DevMenu\DatabaseSeeder as DevMenuDatabaseSeeder ;
use Database\Seeders\SaasMenu\DatabaseSeeder as SaasMenuDatabaseSeeder;
use Database\Seeders\DevMysql\DatabaseSeeder as DevMysqlDatabaseSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DevMenuDatabaseSeeder::class,
            SaasMenuDatabaseSeeder::class,
            DevMysqlDatabaseSeeder::class,
        ]);
    }
}
