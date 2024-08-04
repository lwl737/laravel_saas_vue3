<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use App\Helpers\Func;
class MenuAuthApi extends BaseDev{
     use Cachable;

     public function getApiAttribute()
     {
         return ['full_url' => Func::baseUrl('/dev/'.$this->attributes['api'] ), 'value' => $this->attributes['api'] ] ;
     }
}
