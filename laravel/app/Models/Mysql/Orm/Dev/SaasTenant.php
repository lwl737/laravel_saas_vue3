<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use App\Models\Mysql\Trait\Status;
use App\Models\Mysql\Trait\Organi;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaasTenant extends BaseDev{
     use Status;
     use SoftDeletes;
     use Organi;
     public  $STATUS_KEY = "status";
}
