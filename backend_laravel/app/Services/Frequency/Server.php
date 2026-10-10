<?php

declare(strict_types=1);

namespace App\Services\Frequency;

use App\Utils\Frequency\Control;

class Server extends \App\Services\BaseService
{

    public static function client()
    {
        return new Control(60, 100, 'ADMIN',   [
            3 * 60,      //第一次封禁时间
            3 * 60,      //第二次封禁时间
        ]);
    }
}
