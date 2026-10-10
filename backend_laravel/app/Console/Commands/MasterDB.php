<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MasterDB extends Command
{
    /**
     * 执行数据库迁移的数据库
     *
     * @var string
     */
    protected $signature = 'master_db {--timeout=120s}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */


    public function handle()
    {


        $db_connection = config("database.default");

        $timeout = $this->option('timeout');


        $host = config("database.connections." . $db_connection . ".host");
        $username = config("database.connections." . $db_connection . ".username");
        $password = config("database.connections." . $db_connection . ".password");
        $port = config("database.connections." . $db_connection . ".port");
        $database = config("database.connections." . $db_connection . ".database");


        echo  "wait4x mysql $username:$password@tcp($host:$port)/$database --timeout " . $timeout;
    }
}
