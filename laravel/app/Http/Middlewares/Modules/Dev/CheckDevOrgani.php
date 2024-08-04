<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Helpers\Enums\HeaderReq;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Services\Dev\AdminsLoginInfo;
use App\Helpers\Inject;
use App\Dao\Mysql\Dev\OrganiDao\Orm as OrganiDao;
use App\Dao\Mysql\Dev\OrganiRelationDao\Orm as OrganiRelationDao;

class CheckDevOrgani
{
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $organi_id = $request->header(HeaderReq::ORGANI_ID->value , -1 ); //没有传就是0

        $adminsLoginInfo = Inject::get(AdminsLoginInfo::class);

        //组织id错误
        if( !$organi_id && !preg_match('/(^[1-9][0-9]+$)|(^[1-9]$)|-1/', $organi_id)) return Func::toHttpEnum(Dev::ORGANI_ID_ERROR);

        $organi_id = (int)$organi_id;
        //不是超级管理和开发者 不能看到所有权限


        $organi_name = $organi_id === -1 ? Func::getAllOrganiName() : OrganiDao::static()->whereOrganiId($organi_id)->query()->first(["organi_name"])?->organi_name;
        //组织不存在
        if( !$organi_name ) return Func::toHttpEnum(Dev::LOGIN_ORGANI_NOT_EXIST);

        $result = OrganiRelationDao::static()->checkIsEnable( $adminsLoginInfo->getPrimaryKey() , $organi_id , $adminsLoginInfo->getInfo(['role_id'])["role_id"] );

        //组织没绑定 或者不存在
        if(
          !$result
        )  return Func::toHttpEnum(Dev::LOGIN_ORGANI_NOT_EXIST);

        //存入当前组织
        Inject::bind( AdminsLoginInfo::class , $adminsLoginInfo->setLoginOrganiId($organi_id)->setLoginOrganiName($organi_name)->setLoginOrganiLink($result) );

        return $next($request);
    }
}
