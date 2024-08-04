<?php

declare(strict_types=1);
namespace App\Models\Mongo;
use MongoDB\Client;
use App\Helpers\Func;
class Connect
{
     private static Client|null $driver = null;


     private function __construct(){} //禁止实例化
     private function __clone(){}     //禁止克隆
     /**
      * @description: 连接
      * @param  {array} $postData
      */
     public static  function getDriver() :Client
     {
         if(!self::$driver instanceof Client)  self::$driver = new Client(
            'mongodb://'.config('database.connections.mongo.host').':'.config('database.connections.mongo.port').'/',
            [
            'username' => config('database.connections.mongo.username'),
            'password' =>  config('database.connections.mongo.password')
        ]);

         return self::$driver;
     }


     public static function get()
     {
         self::getDriver();
         $class = explode('\\',get_called_class());
         if(count( $class) === 1) return self::$driver->$class[0]->none;
         $gather = Func::toUnderScore(array_pop($class));
         $db = Func::toUnderScore(array_pop($class));
         return self::$driver->$db->$gather;
     }





}
