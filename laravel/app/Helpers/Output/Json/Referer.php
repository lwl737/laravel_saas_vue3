<?php

declare(strict_types=1);

namespace App\Helpers\Output\Json;

use App\Helpers\Output\Interface\Json;
use App\Helpers\Enums\TrueCode;

enum Referer implements Json
{

    case  REQ_ILLEGAL;  //请求不合法

    public function text(): string
    {
        /* 成功时定义 */
        return match ($this) {
            Referer::REQ_ILLEGAL => '资源不存在~',
        };
    }

    public function errorCode(): int
    {
        /* 成功时定义 */
        return match ($this) {
            Referer::REQ_ILLEGAL => 91001,
        };
    }

    public function trueCode(): TrueCode
    {
        return match ($this) {
            Referer::REQ_ILLEGAL => TrueCode::NOT_FOUND,
            default => TrueCode::BAD_REQUEST
        };
    }
}
