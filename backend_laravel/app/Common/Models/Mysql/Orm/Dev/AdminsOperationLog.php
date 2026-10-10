<?php

declare(strict_types=1);
namespace App\Common\Models\Mysql\Orm\Dev;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use App\Casts\Serialize;
use App\Common\Models\Mysql\Trait\Organi;

class AdminsOperationLog extends BaseDev{
    //  use Cachable;
     use Organi;
     protected $casts = [
        'params' =>  Serialize::class,
        'sql' =>  Serialize::class,
        'enum' =>  Serialize::class,
    ];
}
