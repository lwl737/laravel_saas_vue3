<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Helpers\Output\Json\Dev;
use App\Helpers\Output\Json\Success;
use App\Common\Repository\Dev\AdminsRepository;

class SuperAdmin extends BaseController
{
    public function list(AdminsRepository $adminRepository)
    {

        ["total" => $total, "list" => $list] = $adminRepository->superAdminList($this->params);

        return $this->outputList(
            total: $total,
            list: $list
        );
    }

    public function add(AdminsRepository $adminRepository)
    {
        //判断用户名是否重复
        if ($adminRepository->checkUsername($this->params["username"])) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $adminRepository->superAdminAdd($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function editStatus(int $admins_id, AdminsRepository $adminRepository)
    {
        $adminRepository->superAdmin()->editsStatus($admins_id, $this->params['status']);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    public function del(AdminsRepository $adminRepository)
    {
        $adminRepository->superAdmin()->del($this->params['ids']);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function edit(int $admins_id, AdminsRepository $adminRepository)
    {
        if ($adminRepository->checkUsername($this->params["username"], $admins_id))  return  $this->outputEnum(Dev::USERNAME_IS_EXIST);
        //编辑成功
        $adminRepository->superAdmin()->edit($admins_id, $this->params);

        return  $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
