<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;

use App\Dao\Mysql\Dev\AdminsDao\Orm as AdminsDao;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Success;
use App\Services\Dev\{AdminsLoginInfo, Permissions};
use App\Helpers\Inject;
use App\Dao\Mysql\Dev\RoleDao\Orm as RoleDao;
use App\Dao\Mysql\Dev\RoleDao\Enums\RoleEnable as RoleEnable;
use App\Dao\Mysql\Dev\OrganiAdminsBindDao\Orm as OrganiAdminsBindDao;
use App\Helpers\Func;
use App\Dao\Mysql\Dev\OrganiDao\Orm as OrganiDao;
use Illuminate\Support\Facades\DB;

class Admin extends BaseController
{


    public function login()
    {
        $res = AdminsDao::static()->checkUserPass($this->params)
        ->query()
        ->first(['status', 'admins_id', 'role_id', 'username', 'password']);

        //账号密码错误
        if (!$res) return $this->outputEnum(Dev::USER_PASS_ERROR);

        //账号被禁用
        if (!$res->isEnable()) return $this->outputEnum(Dev::USER_DISABLE);

        if ($res->role_id > 0) {
            $role_res =  RoleDao::static()->checkRoleEnable($res->toArray());
            if ($role_res instanceof RoleEnable) return match ($role_res) {
                //权限不存在
                RoleEnable::NOT_EXIST => $this->outputEnum(Dev::ROLE_NOT_EXIST),
                //权限被禁用
                RoleEnable::STATUS_NOT_ENABLE => $this->outputEnum(Dev::ROLE_DISABLE),
                default => $this->outputEnum(Dev::LOGIN_TOKEN_ERROR)
            };

            $organi_id = OrganiAdminsBindDao::static()->adminsOrganiIdFirst($res->admins_id);

            //组织不存在
            if (!$organi_id) return $this->outputEnum(Dev::ORGANI_NOT_EXIST);
        } else $organi_id = -1;

        return  $this->output(data: ["organi_id" => $organi_id], http: Success::LOGIN_SUCCESS, header: AdminsLoginInfo::createToken($res->toArray()));
    }


    public function checkLogin()
    {
        $info = Inject::get(AdminsLoginInfo::class)->getInfo(['portrait', 'nick_name', 'role_id', 'real_name', 'username', 'phone']);

        $list_config_organi =  OrganiDao::static()->tree(
            $this->getOrganiId() === -1 ? null : [$this->getOrganiId()]
        );

        if (count($list_config_organi) === 1 && $list_config_organi[0]["organi_id"] === -1) $list_config_organi[0]["organi_id"]  = 0;

        return $this->output([
            'info' => Func::formExtractData($info, ['portrait', 'nick_name', 'role_id', 'real_name', 'username', 'phone']),
            'role_id' => $info['role_id'],
            'list_config_organi' => $list_config_organi,
            'organi_name' => $this->getOrgani("organi_name"),
            ...Permissions::menuButtons()->getMenuButtons($info['role_id'])
        ], Dev::DEV_ADMIN_LOGING);
    }

    public function editUserInfo()
    {

        $admins_id = $this->getAdminsId();

        if (
            AdminsDao::static()->checkUsername(
                $this->params,
                $admins_id
            )->query()->exists()
        ) return $this->outputEnum(Dev::USERNAME_IS_EXIST);

        AdminsDao::static()->editInfo($this->params, $admins_id);

        $jwtData =  Inject::get(AdminsLoginInfo::class)->getJwt();

        $jwtData['username'] = $this->params["username"]; //修改密码 其他不变

        return $this->outputEnum(Success::EDIT_SUCCESS, header: AdminsLoginInfo::createToken($jwtData));
    }

    public function editPass()
    {

        $res = AdminsDao::static()->editPass($this->params,  $this->getAdminsId());

        if (!$res) return $this->outputEnum(Dev::ADMINS_UPDATE_PASS_ERROR);

        $jwtData =  Inject::get(AdminsLoginInfo::class)->getJwt();

        $jwtData['password'] = $this->params["password"]; //修改密码 其他不变

        //修改密码后重新生成jwt
        return $this->outputEnum(Success::EDIT_SUCCESS, header: AdminsLoginInfo::createToken($jwtData));
    }


    public function organi()
    {
        return $this->output([
            "tree" =>  OrganiDao::static()->tree(
                $this->getAdminsLoginInfo()->getInfo(['role_id'])["role_id"] > 0 ?
                    OrganiAdminsBindDao::static()->findAdminsOrganiIds($this->getAdminsId())
                    : null
            )
        ], Success::SEL_SUCCESS);
    }

    public function list()
    {
        //排除自己的部门
        $organi_link = $this->getOrganiLinkExcludeSelf();

        if (!empty($organi_link)) {
            if( isset( $this->params["organi_id"] ) && !$this->checkOrgani( $this->params["organi_id"] , false )  ) return $this->outputEnum( Dev::ORGANI_NOT_EXIST);
            $dao =  AdminsDao::static()->listConfig($this->params, $organi_link);
            return $this->outputList(total: $dao->rollCount(), list: $dao->getList($this->params));
        } else return $this->outputList(list: [], total: 0);
    }

    public function add()
    {
        //判断用户名是否重复
        if (AdminsDao::static()->checkUsername(["username" => $this->params["username"]])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $role = RoleDao::static()->organiScope()->checkRoleId(["role_id" => $this->params["role_id"]])->query()->first(['status']);
        //权限不存在
        if (!$role) return $this->outputEnum(Dev::ROLE_NOT_EXIST);
        //权限已被禁用禁用
        if (!$role->isEnable()) return $this->outputEnum(Dev::ROLE_DISABLE);
        //检查部门 排除自己部门 添加和编辑时都要做检查
        if (!$this->checkOrgani($this->params["organi_id"])) return $this->outputEnum(Dev::ORGANI_ERROR);

        AdminsDao::static()->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function editStatus()
    {
        AdminsDao::static()->organiScope()->editStatus($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    public function del()
    {
        AdminsDao::static()->organiScope()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function edit()
    {
        if (AdminsDao::static()->checkUsername([
            "username" => $this->params["username"]
        ], $this->params["admins_id"])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $role = RoleDao::static()->organiScope()->checkRoleId(["role_id" => $this->params["role_id"]])->query()->first(['status']);
        //权限不存在
        if (!$role) return $this->outputEnum(Dev::ROLE_NOT_EXIST);
        //权限已被禁用禁用
        if (!$role->isEnable()) return $this->outputEnum(Dev::ROLE_DISABLE);
        //检查部门 排除自己部门 添加和编辑时都要做检查
        if (!$this->checkOrgani($this->params["organi_id"])) return $this->outputEnum(Dev::ORGANI_ERROR);

        DB::transaction(function () {
            $res = AdminsDao::static()->organiScope()->edit($this->params);

            if ($res) OrganiAdminsBindDao::static()->changeBind($this->params["organi_id"], $this->params["admins_id"]);
        });

        //编辑成功
        return  $this->outputEnum(Success::EDIT_SUCCESS);
    }

    public function roleAll()
    {
        return $this->output([
            'all' => RoleDao::static()->organiScope()->whereStatusEnable()->all()
        ], Success::SEL_SUCCESS);
    }

    public function organiAll()
    {
        return $this->output([
            "tree" =>  OrganiDao::static()->tree(
                $this->getOrganiId() === -1 ? null : $this->getOrganiId(),
                false
            )
        ], Success::SEL_SUCCESS);
    }
}
