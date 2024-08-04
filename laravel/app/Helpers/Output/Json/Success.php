<?php

declare(strict_types=1);

namespace App\Helpers\Output\Json;

use App\Helpers\Output\Interface\Json;
use App\Helpers\Enums\ErrorCode;
use App\Helpers\Enums\TrueCode;
enum Success implements Json
{
        /* 成功时定义 */
    case  MESSAGE;
    case  NOT_DEFIND;
    case  SUCCESS;
    case  MODULE_NOT_DEFIND;
    case  LOGIN_SUCCESS;
    case  SEL_SUCCESS;
    case  ADMIN_LOGINING;
    case  ADD_SUCCESS;
    case  EDIT_SUCCESS;
    case  DEL_SUCCESS;
    case  SET_SUCCESS;
    case  UPLOAD_SUCCESS;
    case  UPLOAD_FIRST_SUCCESS;
    case  CREATE_SUCCESS;
    case  CLEAR_SUCCESS;

    public function text(): string
    {
        /* 成功时定义 */
        return match ($this) {
            Success::MESSAGE => '{message}',
            Success::NOT_DEFIND => '未定义信息',
            Success::SUCCESS => 'success',
            Success::MODULE_NOT_DEFIND => 'module未定义返回信息',
            Success::LOGIN_SUCCESS => '登录成功',
            Success::SEL_SUCCESS => '查询成功',
            Success::ADMIN_LOGINING => '后台还在登录中',
            Success::ADD_SUCCESS => '添加成功',
            Success::EDIT_SUCCESS => '编辑成功',
            Success::DEL_SUCCESS => '删除成功',
            Success::SET_SUCCESS => '设置成功',
            Success::UPLOAD_SUCCESS => '上传成功',
            Success::UPLOAD_FIRST_SUCCESS => '第一次上传成功',
            Success::CREATE_SUCCESS => '生成成功',
            Success::CLEAR_SUCCESS => '清除成功'
        };
    }

    public function errorCode(): int
    {
        /* 成功时定义 */
        return ErrorCode::SUCCESS->value;
    }

    public function trueCode(): TrueCode
    {
        /* 成功时定义 */
        return TrueCode::SUCCESS;
    }
}
