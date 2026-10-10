<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Common\Repository\Dev\MenuAuthApiRepository;

class MenuAuthApi extends BaseController
{


    public function add(MenuAuthApiRepository $menuAuthApiRepository)
    {
        $res = $menuAuthApiRepository->checkApi($this->params);

        if($res) return $this->outputEnum(Dev::API_EXIST,["api_name" => $res->api_name]);

        $menuAuthApiRepository->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 删除 */
    public function del(int $auth_id,MenuAuthApiRepository $menuAuthApiRepository)
    {
        $menuAuthApiRepository->del($auth_id);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /* 编辑 */
    public function edit(int $api_id,MenuAuthApiRepository $menuAuthApiRepository)
    {
        $res =  $menuAuthApiRepository->checkApi($this->params , $api_id);

        if($res) return $this->outputEnum(Dev::API_EXIST,["api_name" => $res->api_name]);

        $menuAuthApiRepository->edit($api_id,$this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    /* 是否添加日志 */
    public function addLog(int $api_id,MenuAuthApiRepository $menuAuthApiRepository)
    {
        $menuAuthApiRepository->edit( $api_id , ['add_log' => $this->params["add_log"] ]);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

}
