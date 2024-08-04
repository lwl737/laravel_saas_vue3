<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Dao\Mysql\Dev\AdminsDao\Orm as AdminsDao;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Success;


class SuperAdmin extends BaseController
{
    public function list()
    {
        //排除自己的部门
        $dao = AdminsDao::static()->superAdminListConfig($this->params);
        return $this->outputList(
            total: $dao->query()->count(),
            list: $dao->list($this->params)->query()->get([
                'admins.admins_id',
                'admins.username',
                'admins.nick_name',
                'admins.real_name',
                'admins.phone',
                'admins.status',
                'admins.role_id',
                'admins.portrait',
            ])
        );
    }

    public function add()
    {
        //判断用户名是否重复
        if (AdminsDao::static()->checkUsername([
            "username" => $this->params["username"]
        ])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        AdminsDao::static()->superAdminAdd($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function editStatus()
    {
        AdminsDao::static()->superAdmin()->editStatus($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    public function del()
    {
        AdminsDao::static()->superAdmin()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function edit()
    {
        if (AdminsDao::static()->checkUsername([
            "username" => $this->params["username"]
        ], $this->params["admins_id"])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        AdminsDao::static()->superAdminEdit($this->params);
        //编辑成功
        return  $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
