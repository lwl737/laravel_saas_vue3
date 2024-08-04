<?php

declare(strict_types=1);

namespace App\Helpers\Output\Json;

use App\Helpers\Output\Interface\Json;
use App\Helpers\Enums\TrueCode;
use App\Helpers\Enums\ErrorCode;

enum Saas implements Json
{
    case  TENANT_ID_ERROR;
    case  TENANT_ID_NOT_EXIST;
    case  TENANT_ID_DISABLE;
    case  USER_PASS_ERROR;
    case  ADMINS_PASS_ERROR;
    case  USER_DISABLE;
    case  ADMINS_UPDATE_PASS_ERROR;
    case  LOGIN_TOKEN_TIMEOUT;
    case  LOGIN_TOKEN_ERROR;
    case  LOGIN_TOKEN_NOT_EXIST;
    case  LOGIN_USER_PASS_CHANGE;
    case  LOGIN_USER_DISABLE;
    case  LOGIN_NOT_EXIST;
    case  ROLE_NOT_EXIST;
    case  ROLE_DISABLE;
    case  LOGIN_ROLE_NOT_EXIST;
    case  LOGIN_ROLE_DISABLE;
    case  LOGIN_ORGANI_NOT_EXIST;
    case  SAAS_ADMIN_LOGING;
    case  USERNAME_IS_EXIST;
    case  ORGANI_ERROR;
    case  ORGANI_NOT_EXIST;
    case  ORGANI_ID_ERROR;
    case  WEB_ROUTER_PATH_ERROR;
    case  PREFIX_ERROR;
    case  NOT_AUTH;



    public function text(): string
    {
        return match ($this) {
            Saas::TENANT_ID_ERROR => "租户ID错误",
            Saas::USER_PASS_ERROR => '账号密码错误',
            Saas::ADMINS_UPDATE_PASS_ERROR => '密码错误',
            Saas::USER_DISABLE => '该账号已被禁用',
            Saas::LOGIN_TOKEN_TIMEOUT => '登录已过期',
            Saas::LOGIN_TOKEN_ERROR => '登录token错误',
            Saas::LOGIN_TOKEN_NOT_EXIST => 'token不存在',
            Saas::LOGIN_USER_PASS_CHANGE => '账号密码修改请重新登录',
            Saas::LOGIN_USER_DISABLE => '该账号已被禁用',
            Saas::LOGIN_NOT_EXIST => '账号不存在',
            Saas::ROLE_NOT_EXIST => '权限不存在',
            Saas::ROLE_DISABLE => '权限被禁用',
            Saas::LOGIN_ROLE_NOT_EXIST => '权限不存在',
            Saas::LOGIN_ROLE_DISABLE => '权限被禁用',
            Saas::LOGIN_ORGANI_NOT_EXIST  => '组织不存在',
            Saas::ORGANI_NOT_EXIST  => '没有该组织的权限',
            Saas::SAAS_ADMIN_LOGING  => '后台还在登录中',
            Saas::USERNAME_IS_EXIST => '账号已存在',
            Saas::ORGANI_ERROR => '所选部门错误',
            Saas::ORGANI_ID_ERROR  => 'organi_id错误',
            Saas::TENANT_ID_NOT_EXIST  => '租户不存在',
            Saas::TENANT_ID_DISABLE  => '租户已被禁用',
            Saas::PREFIX_ERROR  => '路径前缀错误',
            Saas::WEB_ROUTER_PATH_ERROR =>  '没有传web当前路由',
            Saas::NOT_AUTH =>  '{menu_title}下没有{auth_name}权限',


        };
    }

    public function errorCode(): int
    {
        return match ($this) {
            Saas::USER_PASS_ERROR => 10001,
            Saas::USER_DISABLE => 10002,
            Saas::ROLE_NOT_EXIST => 10003,
            Saas::ROLE_DISABLE => 10004,
            Saas::ADMINS_PASS_ERROR => 10008,
            Saas::ADMINS_UPDATE_PASS_ERROR => 10009,
            Saas::LOGIN_TOKEN_ERROR => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_TOKEN_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_USER_PASS_CHANGE => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_USER_DISABLE => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_ROLE_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_ROLE_DISABLE => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_ORGANI_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Saas::TENANT_ID_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Saas::USERNAME_IS_EXIST => 10007,
            Saas::ORGANI_NOT_EXIST =>  10010,
            Saas::ORGANI_ERROR => 10014,
            Saas::PREFIX_ERROR => ErrorCode::LOGINOUT->value,
            Saas::TENANT_ID_ERROR => ErrorCode::LOGINOUT->value,
            Saas::LOGIN_TOKEN_TIMEOUT => ErrorCode::LOGINOUT->value,
            Saas::ORGANI_ID_ERROR =>  ErrorCode::LOGINOUT->value,
            Saas::TENANT_ID_DISABLE =>  ErrorCode::LOGINOUT->value,
            Saas::WEB_ROUTER_PATH_ERROR => 10012,
            Saas::NOT_AUTH => 10011,
            default => ErrorCode::SUCCESS->value,
        };
    }

    public function trueCode(): TrueCode
    {
        return match ($this) {
            default => TrueCode::SUCCESS
        };
    }
}
