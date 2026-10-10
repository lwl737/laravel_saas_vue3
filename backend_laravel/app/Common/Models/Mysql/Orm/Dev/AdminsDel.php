<?php

declare(strict_types=1);
namespace App\Common\Models\Mysql\Orm\Dev;
use App\Casts\Serialize;

class AdminsDel extends BaseDev{
    protected $casts = [
        'data' =>  Serialize::class,
     ];
}
