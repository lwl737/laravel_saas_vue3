<?php

declare(strict_types=1);

namespace App\Helpers\Output\Json;

use App\Helpers\Output\Interface\Json;
use App\Helpers\Enums\TrueCode;

enum Error implements Json
{
    case  MESSAGE;
    case  SERVER_ERROR;
    case  FILE_READ_ERROR;
    case  NOT_TMP_PATH;
    case  NOT_FILE_TYPE;
    case  ADMINS_ID_NOT_DEFIND;
    case  ADMINS_STATUS_NOT_DEFIND;
    case  MODULE_PARAMS_ERROR;
    case  CLASS_NOT_DEFIND;
    case  ORM_CLASS_ERROR;
    case  DEV_ACCOUNT_CREATE_FAIL;
    case  NOT_FIND_FILE_OID;
    case  MOVE_FILE_ERROR;
    case  MODULES_FUNC_NOT_FIND;
        /* 403时定义 */
    case  NOT_AUTH;
        /* 404时定义 */
    case  API_NOT_FIND;
        /* 405时定义 */
    case  API_METHOD_ERROR;
    case  FORM_VALID_ERROR;

    case  PATH_NOT_DEFIND;
    case  ROUTES_CLASS_NOT_DEFIND;
    case  ROUTES_CLASS_ERROR;
    case  INJECT_CLASS_ERROR;
    case  MODULES_PARAMS_ERROR;
    case  CONTROLLER_MUST_STRING;
    case  HEADER_PARAMS_ERROR;

    case  TOKEN_NOT_HAVE ; //token不存在
    case  LOGIN_OUT   ;
    case  TOKEN_ERROR ;

    case  UPLOAD_FILE_TYPE_ERROR;
    case  UPLOAD_FILE_SIZE_ERROR;
    case  UPLOAD_CAP_OVER;
    case  UPLOAD_FIRST_SUCCESS;
    case  SCHEDULE_CHANGE_SUCCESS;
    case  P_DIR_NOT_DEFINED;
    case  DIR_MORE_LEVE;
    case  DIR_MORE_NUM;
    case  EDIT_ERROR;
    public function text(): string
    {
        /* 成功时定义 */
        return match ($this) {
            Error::MESSAGE => '{message}',
            Error::SERVER_ERROR => '系统错误',
            Error::FILE_READ_ERROR => '文件读取错误,可能是没有权限',
            Error::NOT_TMP_PATH =>  '文件临时路径不存在',
            Error::NOT_FILE_TYPE =>  '文件类型不为空',
            Error::ADMINS_ID_NOT_DEFIND => '没有admins_id字段',
            Error::ADMINS_STATUS_NOT_DEFIND => '没有status字段',
            Error::MODULE_PARAMS_ERROR => 'module参数错误',
            Error::ORM_CLASS_ERROR => '传入的orm类错误',
            Error::DEV_ACCOUNT_CREATE_FAIL => '开发者账号生成失败',

            Error::UPLOAD_FILE_TYPE_ERROR => '{file_name}文件类型暂不支持上传',
            Error::UPLOAD_FILE_SIZE_ERROR => '{file_name}文件大小暂不支持上传',
            Error::NOT_FIND_FILE_OID => '上传失败,请重新上传',
            Error::MOVE_FILE_ERROR => '文件移动失败,可能是权限不够',
            Error::MODULES_FUNC_NOT_FIND =>  '{class_name}没有{func}方法',
            /* 403时定义 */
            Error::NOT_AUTH => '没有该接口权限',

            /* 404时定义 */
            Error::API_NOT_FIND => '该资源不存在~',

            /* 405时定义 */
            Error::API_METHOD_ERROR => '{method}请求方式错误',

            Error::FORM_VALID_ERROR => '表单验证错误',
            Error::CLASS_NOT_DEFIND => '路由文件夹里{class_name}该类不存在',


            Error::PATH_NOT_DEFIND => '{path},路由path未定义',
            Error::ROUTES_CLASS_NOT_DEFIND => '路由文件夹里{class_name}该类不存在',
            Error::ROUTES_CLASS_ERROR => '路由文件错误不是{interface}的实例化',
            Error::INJECT_CLASS_ERROR => '{class_name}类定义错误非{interface}的实例化',
            Error::MODULES_PARAMS_ERROR => '{file}路由{route_name} module必须为字符串或者长度为二的list结构数组',
            Error::CONTROLLER_MUST_STRING => '{file}路由{route_name} controller必须为字符串',
            Error::HEADER_PARAMS_ERROR => 'header参数错误',

            Error::TOKEN_NOT_HAVE => '非法请求',
            Error::LOGIN_OUT => '登录已过期',
            Error::TOKEN_ERROR => 'token错误',

            Error::UPLOAD_FILE_TYPE_ERROR => '上传文件类型错误',
            Error::UPLOAD_FILE_SIZE_ERROR => '上传文件大小错误',
            Error::UPLOAD_FIRST_SUCCESS => '第一次上传成功',
            Error::P_DIR_NOT_DEFINED => '父级文件夹未定义',
            Error::DIR_MORE_LEVE => '文件夹最多{level}级',
            Error::DIR_MORE_NUM => '文件夹最多{num}个',
            Error::SCHEDULE_CHANGE_SUCCESS => '修改上传进度成功',
            Error::UPLOAD_CAP_OVER => '文件总容量不足，超出{cap}，请删除素材库文件',
            Error::EDIT_ERROR => '编辑失败',
        };
    }

    public function errorCode(): int
    {
        /* 成功时定义 */
        return match ($this) {
            Error::SERVER_ERROR => 50012,
            Error::FILE_READ_ERROR => 50013,
            Error::NOT_TMP_PATH =>  50014,
            Error::NOT_FILE_TYPE =>  50015,
            Error::ADMINS_ID_NOT_DEFIND => 50016,
            Error::ADMINS_STATUS_NOT_DEFIND =>  50017,
            Error::MODULE_PARAMS_ERROR => 50020,
            Error::ORM_CLASS_ERROR => 50021,
            Error::DEV_ACCOUNT_CREATE_FAIL => 50022,
            Error::UPLOAD_FILE_TYPE_ERROR => 50025,
            Error::UPLOAD_FILE_SIZE_ERROR => 50026,
            Error::NOT_FIND_FILE_OID => 50027,
            Error::MOVE_FILE_ERROR => 50028,
            Error::MODULES_FUNC_NOT_FIND =>  50029,
            /* 403时定义 */
            Error::NOT_AUTH => 50030,

            /* 404时定义 */
            Error::API_NOT_FIND => 50031,

            /* 405时定义 */
            Error::API_METHOD_ERROR => 50032,

            Error::FORM_VALID_ERROR => 50033,
            Error::MESSAGE => 50034,
            Error::CLASS_NOT_DEFIND => 50035,

            Error::PATH_NOT_DEFIND => 50036,
            Error::ROUTES_CLASS_NOT_DEFIND => 50037,
            Error::ROUTES_CLASS_ERROR => 50038,
            Error::INJECT_CLASS_ERROR => 50039,
            Error::MODULES_PARAMS_ERROR => 50040,
            Error::CONTROLLER_MUST_STRING => 50041,
            Error::HEADER_PARAMS_ERROR => 50042,

            Error::TOKEN_NOT_HAVE => 50043,
            Error::TOKEN_ERROR => 50044,
            Error::UPLOAD_FILE_TYPE_ERROR => 50045,
            Error::UPLOAD_FILE_SIZE_ERROR => 50046,
            // Upload::UPLOAD_FIRST_SUCCESS => ErrorCode::SUCCESS->value,
            Error::P_DIR_NOT_DEFINED => 50047,
            Error::DIR_MORE_LEVE => 50048,
            Error::UPLOAD_CAP_OVER => 50049,
            Error::DIR_MORE_NUM => 50050,
            Error::EDIT_ERROR => 50051,
            Error::LOGIN_OUT =>  \App\Helpers\Enums\ErrorCode::LOGINOUT->value,

        };
    }

    public function trueCode(): TrueCode
    {
        return match ($this) {
            Error::NOT_AUTH => TrueCode::FORBIDDEN,
            Error::API_NOT_FIND => TrueCode::NOT_FOUND,
            Error::API_METHOD_ERROR => TrueCode::NOT_ALLOWED,
            Error::FORM_VALID_ERROR => TrueCode::DATA_ERROR,
            Error::CLASS_NOT_DEFIND => TrueCode::NOT_FOUND,
            Error::LOGIN_OUT => TrueCode::SUCCESS,
            Error::TOKEN_NOT_HAVE => TrueCode::BAD_REQUEST,
            Error::TOKEN_ERROR => TrueCode::BAD_REQUEST,

            Error::UPLOAD_FILE_TYPE_ERROR => TrueCode::SUCCESS,
            Error::UPLOAD_FILE_SIZE_ERROR => TrueCode::SUCCESS,
            Error::P_DIR_NOT_DEFINED => TrueCode::SUCCESS,
            Error::DIR_MORE_LEVE => TrueCode::SUCCESS,
            Error::UPLOAD_CAP_OVER => TrueCode::SUCCESS,
            Error::DIR_MORE_NUM => TrueCode::SUCCESS,

            default => TrueCode::SERVER_ERROR
        };
    }
}
