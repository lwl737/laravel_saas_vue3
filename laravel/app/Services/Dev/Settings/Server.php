<?php

declare(strict_types=1);

namespace App\Services\Dev\Settings;


use App\Utils\Settings\Control;
use App\Models\Mysql\Orm\Dev\Settings;

class Server
{
    public static function dev(): Control
    {
        return new Control(Dev::class, new Settings);
    }
}
