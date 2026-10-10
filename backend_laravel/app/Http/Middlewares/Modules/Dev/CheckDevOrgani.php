<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Helpers\Enums\HeaderReq;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Http\Services\Admin\LoginInfo\Dev\ServiceProvider as AdminsLoginInfo;
use App\Common\Repository\Dev\OrganiRelationRepository;
use App\Common\Repository\Dev\OrganiRepository;

class CheckDevOrgani
{
    public function __construct(
        public readonly  AdminsLoginInfo $adminsLoginInfo,
        public readonly  OrganiRelationRepository $organiRelationRepository,
        public readonly  OrganiRepository $organiRepository,
      )
      {}

    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {


        $organi_id = $request->header(HeaderReq::ORGANI_ID->value , -1 ); //没有传就是0


        //组织id错误
        if( !$organi_id && !preg_match('/(^[1-9][0-9]+$)|(^[1-9]$)|-1/', $organi_id)) return Func::toHttpEnum(Dev::ORGANI_ID_ERROR);

        $organi_id = (int)$organi_id;
        //不是超级管理和开发者 不能看到所有权限
        $organi_name = $organi_id === -1 ? Func::getAllOrganiName() : $this->organiRepository->getById($organi_id,["organi_name"])?->organi_name;
        //组织不存在
        if( !$organi_name ) return Func::toHttpEnum(Dev::LOGIN_ORGANI_NOT_EXIST);

        $result = $this->organiRelationRepository->checkIsEnable( $this->adminsLoginInfo->getPrimaryKey() , $organi_id , $this->adminsLoginInfo->getInfo(['role_id'])["role_id"] );

        //组织没绑定 或者不存在
        if(
          !$result
        )  return Func::toHttpEnum(Dev::LOGIN_ORGANI_NOT_EXIST);

        //存入当前组织
        $this->adminsLoginInfo->setLoginOrganiId($organi_id)->setLoginOrganiName($organi_name)->setLoginOrganiLink($result) ;

        return $next($request);
    }
}
