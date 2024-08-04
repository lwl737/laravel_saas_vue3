<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Helpers\Output\Json\Saas;
use App\Helpers\Func;
use App\Dao\Mysql\Saas\AdminsDao\Orm as AdminsDao;
use App\Dao\Mysql\Saas\RoleDao\Orm as RoleDao;
use App\Dao\Mysql\Saas\RoleDao\Enums\RoleEnable;
use App\Dao\Mysql\Saas\AdminsDao\Enums\UserEnable;
use App\Services\Saas\AdminsLoginInfo;
use App\Helpers\Inject;
use App\Helpers\Output\Json;

class CheckSaasLoginEnable
{
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {

        $adminsLoginInfo = Inject::get(AdminsLoginInfo::class);

        $jwt = $adminsLoginInfo->getJwt();

        $is_over = $adminsLoginInfo->getIsOver();

        $admins_res = AdminsDao::static()->checkUserEnable($jwt);

        if ($admins_res instanceof UserEnable) return match ($admins_res) {
            //账号不存在
            UserEnable::NOT_EXIST => Func::toHttpEnum(Saas::LOGIN_NOT_EXIST),
            //账号密码修改过
            UserEnable::USER_PASS_ERROR => Func::toHttpEnum(Saas::LOGIN_USER_PASS_CHANGE),
            //账号被禁用
            UserEnable::STATUS_NOT_ENABLE => Func::toHttpEnum(Saas::LOGIN_USER_DISABLE),
            default => Saas::LOGIN_TOKEN_ERROR
        };

        if ($admins_res->role_id > 0) {
            $role_res =  RoleDao::static()->checkRoleEnable($admins_res->toArray());

            if ($role_res instanceof RoleEnable) return match ($role_res) {
                //权限不存在
                RoleEnable::NOT_EXIST => Func::toHttpEnum(Saas::ROLE_NOT_EXIST),
                //权限被禁用
                RoleEnable::STATUS_NOT_ENABLE => Func::toHttpEnum(Saas::ROLE_DISABLE),
                default => Func::toHttpEnum(Saas::LOGIN_TOKEN_ERROR)
            };
        } else $role_res = null;

        //把登录信息传给后面
        Inject::bind(AdminsLoginInfo::class, $adminsLoginInfo->setAdminsModel($admins_res)->setRoleModel($role_res));

        if ($is_over) Inject::bind(Json::class, new Json(header: AdminsLoginInfo::createToken($jwt)));

        return $next($request);
    }
}
