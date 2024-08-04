<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Saas\AdminsDao\Enums;

enum UserEnable:int{
    case   NOT_EXIST     =-1;    //账号不存在
    case   USER_PASS_ERROR  =-2; //账号密码错误
    case   STATUS_NOT_ENABLE=-3;     //状态错误
}
