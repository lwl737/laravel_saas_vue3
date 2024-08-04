<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Services\Dev\AdminsLoginInfo;
use App\Helpers\Inject;

class CheckDevDevelopAuth
{
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $res = Inject::get(AdminsLoginInfo::class);

        $info = $res->getInfo(["role_id"]);

        if( $info["role_id"] != -1 ) return Func::toHttpEnum(Dev::NOT_DEVELOP_AUTH) ;

        return $next($request);
    }
}
