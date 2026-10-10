<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Http\Services\Admin\LoginInfo\Dev\ServiceProvider as AdminsLoginInfo;

class CheckDevDevelopAuth
{
    public function __construct(
        public readonly  AdminsLoginInfo $adminsLoginInfo,
    ) {}

    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {

        $info = $this->adminsLoginInfo->getInfo(["role_id"]);

        if ($info["role_id"] != -1) return Func::toHttpEnum(Dev::NOT_DEVELOP_AUTH);

        return $next($request);
    }
}
