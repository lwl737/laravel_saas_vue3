<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Dev\RoleDao\Enums;

enum RoleEnable:int{
    case   NOT_EXIST     =-1;    //权限不存在
    case   STATUS_NOT_ENABLE= -2;     //被禁用
}
