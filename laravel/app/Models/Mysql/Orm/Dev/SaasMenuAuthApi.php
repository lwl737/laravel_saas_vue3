<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use App\Helpers\Func;
class SaasMenuAuthApi extends BaseDev{
     use Cachable;

     public function getApiAttribute()
     {
         return ['full_url' => Func::baseUrl('/saas/'.$this->attributes['api'] ), 'value' => $this->attributes['api'] ] ;
     }
}
