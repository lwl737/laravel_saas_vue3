<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Saas;
use App\Models\Mysql\Trait\Status;
use App\Models\Mysql\Trait\Organi;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\Serialize;

class Role extends BaseSaas{
     use Status;
     use SoftDeletes;
     use Organi;
     public  $STATUS_KEY = "status";

     protected $casts = [
        'role_json' =>  Serialize::class,
    ];
}
