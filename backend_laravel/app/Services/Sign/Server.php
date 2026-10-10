<?php

declare(strict_types=1);

namespace App\Services\Sign;

use App\Utils\Sign\Control as Sign;

class Server extends \App\Services\BaseService
{

    public static function md5(array  $params, string $headerSign): Sign
    {
        return new Sign($params, $headerSign, fn (string $str) => md5($str));
    }
}
