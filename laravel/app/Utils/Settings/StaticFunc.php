<?php

declare(strict_types=1);
namespace App\Utils\Settings;

use App\Helpers\Func;

trait StaticFunc {
    public static function all()
    {
        $default = [];
        foreach (static::cases() as $val)   $default[$val->name] =  Func::casts($val->casts(), $val->default());
        return  $default;
    }
}
