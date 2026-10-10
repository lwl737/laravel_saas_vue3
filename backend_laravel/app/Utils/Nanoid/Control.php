<?php

declare(strict_types=1);
namespace App\Utils\Nanoid;
use Hidehalo\Nanoid\Client;
class Control{
     public static function create($number = 21){
         $naniod = (new Client)->generateId($number);
         return  $naniod;
     }
}
