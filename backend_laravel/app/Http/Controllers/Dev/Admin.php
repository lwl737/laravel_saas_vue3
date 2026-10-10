<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;

use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Success;
use App\Http\Services\Admin\Permissions\Dev\ServiceProvider as Permissions;
use App\Helpers\Func;
use App\Common\Repository\Dev\AdminsRepository;
use App\Common\Repository\Dev\RoleRepository;
use App\Common\Repository\Dev\OrganiAdminsBindRepository;
use App\Common\Repository\Dev\OrganiRepository;

class Admin extends BaseController
{


    public function login(
        AdminsRepository $adminRepository,
        RoleRepository $roleRepository,
        OrganiAdminsBindRepository $organiAdminsBindRepository
    ) {
        $res = $adminRepository->checkUserPass($this->params);

        //账号密码错误
        if (!$res) return $this->outputEnum(Dev::USER_PASS_ERROR);

        //账号被禁用
        if (!$res->isEnable()) return $this->outputEnum(Dev::USER_DISABLE);

        if ($res->role_id > 0) {

            $roleRes = $roleRepository->checkRoleEnable($res->toArray());

            if ($roleRes instanceof Dev) return $this->outputEnum($roleRes);

            $organi_id = $organiAdminsBindRepository->adminsOrganiIdFirst($res->admins_id);

            //组织不存在
            if (!$organi_id) return $this->outputEnum(Dev::ORGANI_NOT_EXIST);
        } else $organi_id = -1;

        return  $this->output(data: ["organi_id" => $organi_id], http: Success::LOGIN_SUCCESS, header: $this->getAdminsLoginInfo()->createToken($res->toArray()));
    }


    public function checkLogin(OrganiRepository $organiRepository, Permissions $permissions)
    {

        $info = $this->getAdminsLoginInfo()->getInfo(['portrait', 'nick_name', 'role_id', 'real_name', 'username', 'phone']);

        $list_config_organi =  $organiRepository->tree(
            $this->getOrganiId() === -1 ? null : [$this->getOrganiId()],
            false
        );

        if (count($list_config_organi) === 1 && $list_config_organi[0]["organi_id"] === -1) $list_config_organi[0]["organi_id"]  = 0;

        return $this->output([
            'info' => Func::formExtractData($info, ['portrait', 'nick_name', 'role_id', 'real_name', 'username', 'phone']),
            'role_id' => $info['role_id'],
            'list_config_organi' => $list_config_organi,
            'organi_name' => $this->getOrgani("organi_name"),
            ...$permissions->menuButtons()->getMenuButtons($info['role_id'])
        ], Dev::DEV_ADMIN_LOGING);
    }

    public function editUserInfo(
        AdminsRepository $adminRepository
    ) {
        $admins_id = $this->getAdminsId();

        if (
            $adminRepository->checkUsername(
                $this->params["username"],
                $admins_id
            )
        ) return $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $adminRepository->edit($admins_id, $this->params, false);

        $jwtData =  $this->getAdminsLoginInfo()->getJwt();

        $jwtData['username'] = $this->params["username"]; //修改密码 其他不变

        return $this->outputEnum(Success::EDIT_SUCCESS, header: $this->getAdminsLoginInfo()->createToken($jwtData));
    }

    public function editPass(
        AdminsRepository $adminRepository,

    ) {

        $res = $adminRepository->editPass($this->getAdminsId(), $this->params);

        if (!$res) return $this->outputEnum(Dev::ADMINS_UPDATE_PASS_ERROR);

        $jwtData =  $this->getAdminsLoginInfo()->getJwt();

        $jwtData['password'] = $this->params["password"]; //修改密码 其他不变

        //修改密码后重新生成jwt
        return $this->outputEnum(Success::EDIT_SUCCESS, header: $this->getAdminsLoginInfo()->createToken($jwtData));
    }



    public function list(
        AdminsRepository $adminRepository
    ) {
        //排除自己的部门
        $organi_link = $this->getOrganiLinkExcludeSelf();
        if (!empty($organi_link)) {
            if (isset($this->params["organi_id"]) && !$this->checkOrgani($this->params["organi_id"], false)) return $this->outputEnum(Dev::ORGANI_NOT_EXIST);
            ["list" => $list, "total" => $total] =  $adminRepository->list($this->params, $organi_link);
            return $this->outputList(total: $total, list: $list);
        } else return $this->outputList(list: [], total: 0);
    }

    public function add(
        AdminsRepository $adminRepository,
        RoleRepository $roleRepository
    ) {

        //判断用户名是否重复
        if ($adminRepository->checkUsername($this->params["username"])) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $res = $roleRepository->organiScope()->checkRoleById($this->params["role_id"]);

        if ($res instanceof Dev) return $this->outputEnum($res);

        //检查部门 排除自己部门 添加和编辑时都要做检查
        if (!$this->checkOrgani($this->params["organi_id"])) return $this->outputEnum(Dev::ORGANI_ERROR);

        $adminRepository->normalAdd($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function editStatus(
        int $admins_id,
        AdminsRepository $adminRepository
    ) {
        $adminRepository->organiScope()->editsStatus($admins_id, $this->params["status"]);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    public function del(
        AdminsRepository $adminRepository
    ) {
        $adminRepository->organiScope()->del($this->params['ids']);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function edit(
        int $admins_id,
        AdminsRepository $adminRepository,
        RoleRepository $roleRepository,
    ) {
        if ($adminRepository->checkUsername($this->params["username"], $admins_id)) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $res = $roleRepository->organiScope()->checkRoleById($this->params["role_id"]);

        if ($res instanceof Dev) return $this->outputEnum($res);

        //检查部门 排除自己部门 添加和编辑时都要做检查
        if (!$this->checkOrgani($this->params["organi_id"])) return $this->outputEnum(Dev::ORGANI_ERROR);

        $adminRepository->normalEdit($admins_id , $this->params);

        //编辑成功
        return  $this->outputEnum(Success::EDIT_SUCCESS);
    }


}
