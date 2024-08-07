<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Saas;
use App\Casts\Serialize;
use App\Models\Mysql\Trait\Organi;

class AdminsOperationLog extends BaseSaas{
     use Organi;
     protected $casts = [
        'params' =>  Serialize::class,
        'sql' =>  Serialize::class,
        'enum' =>  Serialize::class,
    ];
}
