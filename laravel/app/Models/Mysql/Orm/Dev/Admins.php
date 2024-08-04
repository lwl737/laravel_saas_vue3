<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
// use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use App\Models\Mysql\Trait\Status;
use App\Models\Mysql\Trait\Organi;

class Admins extends BaseDev{
    //  use Cachable;
     use Status;
     use Organi;
     public  $STATUS_KEY = "status";
     public function getPortraitAttribute()
     {
         return  ['full_url' => $this->attributes['portrait'] ? $this->attributes['portrait']  : ""  ,'value' => $this->attributes['portrait']];
     }

     public function role(){
        return  $this->hasOne(Role::class,'role_id','role_id')->select(['role_name','role_id']);
    }
}