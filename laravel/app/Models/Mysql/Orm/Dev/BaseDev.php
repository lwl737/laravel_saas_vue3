<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use App\Models\Mysql\Orm\BaseOrm;

abstract class BaseDev extends BaseOrm{

    public   $connection = 'dev_mysql';
    protected static function boot()
    {
        parent::boot();
    }



}
