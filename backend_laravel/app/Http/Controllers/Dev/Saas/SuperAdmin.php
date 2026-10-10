<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas;

use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Common\Repository\Saas\AdminsRepository;
class SuperAdmin extends BaseController
{

    public function list(AdminsRepository $adminsRepository)
    {
        ["total" => $total , "list" => $list ] = $adminsRepository->tenancyInitialize($this->params['tenant_id'])->superAdminList($this->params);
        return $this->outputList(
            total: $total,
            list: $list,
            addField:['tenant_id' => $this->params['tenant_id'] ]
        );
    }

    public function add(AdminsRepository $adminsRepository)
    {
        //判断用户名是否重复
        if ( $adminsRepository->tenancyInitialize($this->params['tenant_id'])->checkUsername($this->params["username"])){
            return  $this->outputEnum(Dev::USERNAME_IS_EXIST);
        }

        // if (AdminsDao::static([$this->params['tenant_id']])->checkUsername([
        //     "username" => $this->params["username"]
        // ])->query()->exists()) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $adminsRepository->superAdminAdd($this->params($this->formData()));

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function editStatus(int $admins_id , AdminsRepository $adminsRepository)
    {
        $adminsRepository->tenancyInitialize($this->params['tenant_id'])->superAdmin()->edit($admins_id ,[ "status" => $this->params['status']]);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }


    public function del(AdminsRepository $adminsRepository)
    {
        $adminsRepository->tenancyInitialize($this->params['tenant_id'])->superAdmin()->del($this->params['ids']);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }


    public function edit(int $admins_id , AdminsRepository $adminsRepository)
    {
        if ($adminsRepository->tenancyInitialize($this->params['tenant_id'])->checkUsername( $this->params["username"]
        , $admins_id )) return  $this->outputEnum(Dev::USERNAME_IS_EXIST);

        $adminsRepository->superAdmin()->edit($admins_id ,$this->params($this->formData()));
        //编辑成功
        return  $this->outputEnum(Success::EDIT_SUCCESS);
    }

    private function formData(){
        return ["username", "password", "status", "portrait", "nick_name", "real_name", "phone"];
    }
}
