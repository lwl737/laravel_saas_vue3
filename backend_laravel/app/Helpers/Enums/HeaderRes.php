<?php

declare(strict_types=1);
namespace App\Helpers\Enums;
//响应头
enum HeaderRes: string
{
  case  DEV_LOGIN_TOKEN = "dev-access-token";                   //后台登录token
  case  SAAS_LOGIN_TOKEN = "saas-access-token";                   //后台登录token

}

