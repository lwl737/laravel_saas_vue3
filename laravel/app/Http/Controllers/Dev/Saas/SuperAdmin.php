<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas;

use App\Dao\Mysql\Dev\SaasTenantDao\Orm as SaasTenantDao;
use App\Dao\Mysql\Saas\AdminsDao\Orm as AdminsDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Services\Tenant\Server as TenantServer;

class SuperAdmin extends BaseController
{

    public function list()
    {
        if (!isset($this->params['tenant_id']) || !$this->params['tenant_id']) {
            return $this->outputList(
                total: 0,
                list: [],
                addField:['tenant_id' => null ]
            );
        }
        //排除自己的部门
        $dao = AdminsDao::static([$this->params['tenant_id']])->superAdminListConfig($this->params);
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
            ]),
            addField:['tenant_id' => TenantServer::tenancyId() ]
        );
    }

    public function add()
    {
        //判断用户名是否重复
        if (AdminsDao::static([$this->params['tenant_id']])->checkUsername([
            "username" => $this->params["username"]
        ])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        AdminsDao::static([$this->params['tenant_id']])->superAdminAdd($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function editStatus()
    {
        AdminsDao::static([$this->params['tenant_id']])->superAdmin()->editStatus($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    public function del()
    {
        AdminsDao::static([$this->params['tenant_id']])->superAdmin()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function edit()
    {
        if (AdminsDao::static([$this->params['tenant_id']])->checkUsername([
            "username" => $this->params["username"]
        ], $this->params["admins_id"])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        AdminsDao::static([$this->params['tenant_id']])->superAdminEdit($this->params);
        //编辑成功
        return  $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
