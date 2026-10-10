<?php

declare(strict_types=1);

namespace App\Services\Settings;


use App\Utils\Settings\Control;
use App\Common\Models\Mysql\Orm\Dev\Settings;
use App\Services\Settings\Modules\Dev;

class ServiceProvider
{
    public static function dev(): Control
    {
        return new Control(Dev::class, new Settings);
    }
}
