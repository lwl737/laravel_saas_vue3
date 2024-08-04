<?php

declare(strict_types=1);
namespace App\Helpers\Output\Interface;
use App\Helpers\Enums\TrueCode;
interface Json{
    public function text():string ;

    public function errorCode():int ;

    public function trueCode():TrueCode ;
}
