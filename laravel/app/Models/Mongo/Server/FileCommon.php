<?php

declare(strict_types=1);
namespace App\Models\Mongo\Server;

class FileCommon extends BaseServer
{
   protected static string $database = 'file_common';

   protected static string $table = 'admins';

   protected static array  $index = [
        ['admins_id' => 1, 'dir_id' => 1],
   ];  //索引
}
