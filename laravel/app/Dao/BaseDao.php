<?php

declare(strict_types=1);

namespace App\Dao;

use App\Helpers\Inject;
// use PhpParser\Node\Expr\Cast\Object_;

abstract class BaseDao
{
    public final  static function static(array $params = [], bool $reset = false): object
    {

        if ($reset) Inject::bind(static::class, $params);

        return  Inject::bindGet(static::class, $params);
    }
}
