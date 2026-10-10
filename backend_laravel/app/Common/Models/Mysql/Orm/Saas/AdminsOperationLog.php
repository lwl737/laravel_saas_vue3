<?php

declare(strict_types=1);
namespace App\Common\Models\Mysql\Orm\Saas;
use App\Casts\Serialize;
use App\Common\Models\Mysql\Trait\Organi;

class AdminsOperationLog extends BaseSaas{
     use Organi;
     protected $casts = [
        'params' =>  Serialize::class,
        'sql' =>  Serialize::class,
        'enum' =>  Serialize::class,
    ];
}
