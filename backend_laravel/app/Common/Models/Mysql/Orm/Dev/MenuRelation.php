<?php

declare(strict_types=1);
namespace App\Common\Models\Mysql\Orm\Dev;

class MenuRelation extends BaseDev{
    public $timestamps = false;
    public $incrementing = false;  //不是自增id
}
