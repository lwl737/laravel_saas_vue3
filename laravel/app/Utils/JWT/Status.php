<?php

declare(strict_types=1);
namespace App\Utils\JWT;

enum Status:int{
    case   success     = 1;      //成功
    case   timeout     =-1;    //token过期
    case   sign_error  =-2;     //签名错误
    case   before_error=-3;    //签名在某个时间点之后才能用
    case   other_error =-4;    //其他错误
}
