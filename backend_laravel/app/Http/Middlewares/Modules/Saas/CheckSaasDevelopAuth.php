<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Http\Services\Admin\LoginInfo\Saas\ServiceProvider as AdminsLoginInfo;

class CheckSaasDevelopAuth
{
    public function __construct(
        public readonly  AdminsLoginInfo $adminsLoginInfo
    )
    { }
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $info = $this->adminsLoginInfo->getInfo(["role_id"]);

        if( $info["role_id"] != -1 ) return Func::toHttpEnum(Dev::NOT_DEVELOP_AUTH) ;

        return $next($request);
    }
}
