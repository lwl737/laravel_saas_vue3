<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Services\JWT\Server as JWTServer;
use App\Helpers\Enums\HeaderReq;
use App\Helpers\Output\Json\Saas;
use App\Helpers\Func;
use App\Services\Saas\AdminsLoginInfo;
use App\Helpers\Inject;

class CheckSaasLogin
{
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        return JWTServer::saasLogin()->check(
            $request,
            HeaderReq::SAAS_LOGIN_TOKEN,
            function ($jwt, $is_over) use ($next, $request) {
                if (!isset($jwt['username']) || !isset($jwt['password']) || !isset($jwt['admins_id']) || !isset($jwt['tenant_id'])) return Func::toHttpEnum(Saas::LOGIN_TOKEN_ERROR);
                $adminsLoginInfo = Inject::get(AdminsLoginInfo::class);
                //租户id 对不上
                if( $adminsLoginInfo->getTenantId() != $jwt['tenant_id']) return Func::toHttpEnum(Saas::TENANT_ID_ERROR);
                //把登录信息传给后面
                Inject::bind(AdminsLoginInfo::class, $adminsLoginInfo->setJwt($jwt)->setIsOver($is_over));
                //与CheckDevLoginEnable拆成两段中间件
                return $next($request);
            },
            fn () => Func::toHttpEnum(Saas::LOGIN_TOKEN_TIMEOUT),
            fn () => Func::toHttpEnum(Saas::LOGIN_TOKEN_ERROR),
            fn () => Func::toHttpEnum(Saas::LOGIN_TOKEN_NOT_EXIST),
        );
    }
}
