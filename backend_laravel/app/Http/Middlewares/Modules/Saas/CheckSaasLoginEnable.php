<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Helpers\Output\Json\Saas;
use App\Helpers\Func;
use App\Common\Repository\Saas\RoleRepository;
use App\Common\Repository\Saas\AdminsRepository;
use App\Http\Services\Admin\LoginInfo\Saas\ServiceProvider as AdminsLoginInfo;
use App\Http\Systems\CustomRouter;

class CheckSaasLoginEnable
{
    public function __construct(
        public readonly  AdminsLoginInfo $adminsLoginInfo,
        public readonly  CustomRouter $customRouter,
        public readonly  AdminsRepository $adminsRepository,
        public readonly  RoleRepository $roleRepository
    )
    { }

    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {

        // $adminsLoginInfo = Inject::get(AdminsLoginInfo::class);

        $jwt = $this->adminsLoginInfo->getJwt();

        $is_over = $this->adminsLoginInfo->getIsOver();

        $admins_res = $this->adminsRepository->checkUserEnable($jwt);

        if ($admins_res instanceof Saas) return Func::toHttpEnum($admins_res);

        if ($admins_res->role_id > 0) {
            $role_res =  $this->roleRepository->checkRoleEnable($admins_res->toArray());

            if ($role_res instanceof Saas) return Func::toHttpEnum($role_res);
        } else $role_res = null;

        //把登录信息传给后面
        $this->adminsLoginInfo->setAdmins($admins_res)->setRole($role_res);

        if ($is_over) $this->customRouter->getResult()->addHeader($this->adminsLoginInfo->createToken($jwt));

        return $next($request);
    }
}
