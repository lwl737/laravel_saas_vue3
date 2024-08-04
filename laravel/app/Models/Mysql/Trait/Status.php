<?php

declare(strict_types=1);

namespace App\Models\Mysql\Trait;
use App\Models\Mysql\Trait\Enums\Status as StatusEnum;
trait Status {


    public function isEnable(){
        return $this->getKeyValue($this->getStatusKey() , 'status') === $this->getStatusVal("ENABLE");
    }

    public function getStatusKey(){
        return $this->STATUS_KEY ?? 'status';
    }

    public function getStatusVal(string $key){
        return   constant(StatusEnum::class."::".$key)->value;
    }

}
