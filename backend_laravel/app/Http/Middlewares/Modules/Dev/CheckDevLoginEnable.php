<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Http\Services\Admin\LoginInfo\Dev\ServiceProvider as AdminsLoginInfo;
use App\Http\Systems\CustomRouter;
use App\Common\Repository\Dev\AdminsRepository;
use App\Common\Repository\Dev\RoleRepository;

class CheckDevLoginEnable
{
    public function __construct(
        public readonly  AdminsLoginInfo $adminsLoginInfo,
        public readonly  CustomRouter $customRouter,
        public readonly  AdminsRepository $adminsRepository,
        public readonly  RoleRepository $roleRepository
      )
      {}

    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $jwt = $this->adminsLoginInfo->getJwt();

        $is_over =  $this->adminsLoginInfo->getIsOver();

        $admins_res = $this->adminsRepository->checkUserEnable($jwt);

        if ($admins_res instanceof Dev)  return Func::toHttpEnum($admins_res);

        if ($admins_res->role_id > 0) {
            $role_res =  $this->roleRepository->checkRoleEnable($admins_res->toArray() , true );
            if($role_res instanceof Dev) return Func::toHttpEnum($role_res);
        } else $role_res = null;

        //把登录信息传给后面
        $this->adminsLoginInfo->setAdmins($admins_res)->setRole( $role_res );

        /** 登录过期后重新刷新token **/
        if ($is_over) $this->customRouter->getResult()->addHeader($this->adminsLoginInfo->createToken($jwt));

        return $next($request);
    }
}
