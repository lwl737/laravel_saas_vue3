<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(\Database\Seeders\DevMysql\AdminsTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\CityTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\MenuTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\MenuAuthTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\MenuAuthApiTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\MenuAuthButtonsTableSeeder::class);

        $this->call(\Database\Seeders\DevMysql\MenuRelationTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\SaasMenuTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\SaasMenuAuthTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\SaasMenuAuthApiTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\SaasMenuAuthButtonsTableSeeder::class);
        $this->call(\Database\Seeders\DevMysql\SaasMenuRelationTableSeeder::class);

    }
}
