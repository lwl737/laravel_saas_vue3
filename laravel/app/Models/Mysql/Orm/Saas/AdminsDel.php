<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Saas;
use App\Casts\Serialize;

class AdminsDel extends BaseSaas{
    protected $casts = [
        'data' =>  Serialize::class,
     ];
}
