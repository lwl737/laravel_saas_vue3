<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Services\JWT\Server as JWTServer;
use App\Helpers\Enums\HeaderReq;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Services\Dev\AdminsLoginInfo;
use App\Helpers\Inject;

class CheckDevLogin
{
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        return JWTServer::devLogin()->check(
            $request,
            HeaderReq::DEV_LOGIN_TOKEN,
            function ($jwt, $is_over) use ($next, $request) {
                if (!isset($jwt['username']) || !isset($jwt['password']) || !isset($jwt['admins_id'])) return Func::toHttpEnum(Dev::LOGIN_TOKEN_ERROR);
                //把登录信息传给后面
                Inject::bind(AdminsLoginInfo::class, new AdminsLoginInfo(jwt:$jwt,is_over:$is_over));
                //与CheckDevLoginEnable拆成两段中间件
                return $next($request);
            },
            fn () => Func::toHttpEnum(Dev::LOGIN_TOKEN_TIMEOUT),
            fn () => Func::toHttpEnum(Dev::LOGIN_TOKEN_ERROR),
            fn () => Func::toHttpEnum(Dev::LOGIN_TOKEN_NOT_EXIST),
        );
    }
}
