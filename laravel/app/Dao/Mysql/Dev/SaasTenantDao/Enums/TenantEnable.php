<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Dev\SaasTenantDao\Enums;

enum TenantEnable:int{
    case   NOT_EXIST     =-1;    //租户不存在
    case   STATUS_NOT_ENABLE= -2;     //租户被禁用
}
