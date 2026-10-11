<?php

declare(strict_types=1);

namespace App\Http\Services\Test;
class Test {

   public int $num;

   public function __construct($num = 0)
   {
      $this->num = $num;
   }

   public function set($num){
     return  $this->num = $num;
   }

   public function get(){
      return $this->num;
   }
}
