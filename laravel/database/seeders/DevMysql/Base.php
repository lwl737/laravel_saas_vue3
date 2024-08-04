<?php

declare(strict_types=1);

namespace Database\Seeders\DevMysql;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

abstract class Base extends Seeder
{
    public function __construct()
    {
        Config::set("database.default", "dev_mysql");
    }

}
