<?php

declare(strict_types=1);

namespace App\Helpers\Output\Json;

use App\Helpers\Output\Interface\Json;
use App\Helpers\Enums\TrueCode;
use App\Helpers\Enums\ErrorCode;

enum Dev implements Json
{
    case  USER_PASS_ERROR;
    case  ADMINS_PASS_ERROR;
    case  USER_DISABLE;
    case  ADMINS_UPDATE_PASS_ERROR;
    case  LOGIN_TOKEN_TIMEOUT;
    case  LOGIN_TOKEN_ERROR;
    case  LOGIN_TOKEN_NOT_EXIST;
    case  LOGIN_USER_PASS_CHANGE;
    case  LOGIN_USER_DISABLE;
    case  LOGIN_ROLE_NOT_EXIST;
    case  LOGIN_ROLE_DISABLE;
    case  LOGIN_NOT_EXIST;
    case  ROLE_NOT_EXIST;
    case  ROLE_DISABLE;
    case  DEV_ADMIN_LOGING;
    case  MENU_EXIST;
    case  API_EXIST;
    case  NOT_DEVELOP_AUTH;
    case  USERNAME_IS_EXIST;
    case  ORGANI_NOT_EXIST;
    case  LOGIN_ORGANI_NOT_EXIST;
    case  ORGANI_ID_ERROR;
    case  PREFIX_ERROR;
    case  NOT_AUTH;
    case  WEB_ROUTER_PATH_ERROR;
    case  AUTH_NOT_EXIST;
    case  ACCOUNT_IS_EXIST;
    case  ORGANI_ERROR;
    case  PAGES_IS_EXIST;


    public function text(): string
    {
        return match ($this) {
            Dev::USER_PASS_ERROR => '账号密码错误',
            Dev::ADMINS_UPDATE_PASS_ERROR => '密码错误',
            Dev::USER_DISABLE => '该账号已被禁用',
            Dev::LOGIN_TOKEN_TIMEOUT => '登录已过期',
            Dev::LOGIN_TOKEN_ERROR => '登录token错误',
            Dev::LOGIN_TOKEN_NOT_EXIST => 'token不存在',
            Dev::LOGIN_USER_PASS_CHANGE => '账号密码修改请重新登录',
            Dev::LOGIN_USER_DISABLE => '该账号已被禁用',
            Dev::LOGIN_NOT_EXIST => '账号不存在',
            Dev::ROLE_NOT_EXIST => '权限不存在',
            Dev::ROLE_DISABLE => '权限被禁用',
            Dev::LOGIN_ROLE_NOT_EXIST => '权限不存在',
            Dev::LOGIN_ROLE_DISABLE => '权限被禁用',
            Dev::DEV_ADMIN_LOGING => '后台还在登录中',
            Dev::MENU_EXIST => '该path跟栏目{title}重复 menu_id-{menu_id}',
            Dev::API_EXIST => '该api同{api_name}重复',
            Dev::NOT_DEVELOP_AUTH => '没有开发者权限',
            Dev::USERNAME_IS_EXIST => '账号已存在',
            Dev::ORGANI_NOT_EXIST  => '没有该组织的权限',
            Dev::LOGIN_ORGANI_NOT_EXIST  => '组织不存在',
            Dev::ORGANI_ID_ERROR  => 'organi_id错误',
            Dev::ORGANI_ERROR  => '所选部门错误',
            Dev::PREFIX_ERROR  => '路径前缀错误',
            Dev::NOT_AUTH =>  '{menu_title}下没有{auth_name}权限',
            Dev::WEB_ROUTER_PATH_ERROR =>  '没有传web当前路由',
            Dev::AUTH_NOT_EXIST =>  '某权限不存在，请刷新页面重试',
            Dev::PAGES_IS_EXIST =>  '页面已存在{menu_title}',
        };
    }

    public function errorCode(): int
    {
        return match ($this) {
            Dev::USER_PASS_ERROR => 10001,
            Dev::USER_DISABLE => 10002,
            Dev::ROLE_NOT_EXIST => 10003,
            Dev::ROLE_DISABLE => 10004,
            Dev::MENU_EXIST => 10005,
            Dev::API_EXIST => 10006,
            Dev::USERNAME_IS_EXIST => 10007,
            Dev::ADMINS_PASS_ERROR => 10008,
            Dev::ADMINS_UPDATE_PASS_ERROR => 10009,
            Dev::LOGIN_ORGANI_NOT_EXIST => ErrorCode::LOGINOUT->value ,
            Dev::PREFIX_ERROR => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_TOKEN_TIMEOUT =>  ErrorCode::LOGINOUT->value,
            Dev::ORGANI_ID_ERROR =>  ErrorCode::LOGINOUT->value,
            Dev::ORGANI_NOT_EXIST =>  10010 ,
            Dev::LOGIN_TOKEN_ERROR => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_TOKEN_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_USER_PASS_CHANGE => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_USER_DISABLE => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_ROLE_NOT_EXIST => ErrorCode::LOGINOUT->value,
            Dev::LOGIN_ROLE_DISABLE => ErrorCode::LOGINOUT->value,
            Dev::NOT_DEVELOP_AUTH => ErrorCode::LOGINOUT->value,
            Dev::NOT_AUTH => 10011,
            Dev::WEB_ROUTER_PATH_ERROR => 10012,
            Dev::AUTH_NOT_EXIST => 10013,
            Dev::ORGANI_ERROR => 10014,
            Dev::PAGES_IS_EXIST => 10015,
            default => ErrorCode::SUCCESS->value
        };
    }

    public function trueCode(): TrueCode
    {
        return match ($this) {
            default => TrueCode::SUCCESS
        };
    }
}
