<?php

declare(strict_types=1);
namespace App\Models\Mongo\Server;

class DevAdminFile extends BaseServer
{
   protected static string $database = 'dev_admins_file';

   protected static string $table = 'admins';

   protected static array  $index = [
        ['admins_id' => 1, 'dir_link' => 1 , 'create_time' => -1 ],  //查询索引
        ['admins_id' => 1, 'dir_link' => 1  ],  //删除索引
    ];  //索引
}
