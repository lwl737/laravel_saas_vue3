<?php

declare(strict_types=1);

namespace App\Models\Mongo\Server;

class SaasAdminsDir extends BaseServer
{
    protected static string $database = 'saas_admins_dir';

    protected static string $table = 'admins';

    protected static array  $index = [
        ['admins_id' => 1 , 'dir_sort' => -1 ],
        ['_id' => 1 , 'admins_id' => 1]
    ];  //索引

}
