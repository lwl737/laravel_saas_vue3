<?php

declare(strict_types=1);
namespace App\Helpers\Enums;
//内部状态码错误
enum ErrorCode: int
{
  case  SUCCESS = 0;               //成功返回的参数
  case  LOGINOUT = -1;             //登录过期返回的参数
}

