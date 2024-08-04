<?php

declare(strict_types=1);
namespace App\Helpers\Enums;
//$request 信息
enum Attributes: string
{
  case  LOGIN_INFO = 'login_info';       //登录信息
  case  AUTH_NAME = 'auth_name';       //当前权限名称
  case  ADD_LOG = 'add_log';            //是否加入日志
  case  RES_HEADER = 'res_hader' ;       //响应头
  case  MENU_TITLE = 'menu_title' ;       //栏目标题
  case  JWT_INFO = 'jwt_info' ;       //jwt信息
}

