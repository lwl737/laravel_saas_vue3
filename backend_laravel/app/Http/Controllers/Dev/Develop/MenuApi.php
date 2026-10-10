<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Common\Repository\Dev\MenuAuthApiRepository;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;

class MenuApi extends BaseController
{

    public function add(MenuAuthApiRepository $menuAuthApiRepository)
    {
        $res =  $menuAuthApiRepository->checkApi($this->params);

        if ($res) return $this->outputEnum(Dev::API_EXIST, ["api_name" => $res->api_name]);

        $menuAuthApiRepository->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 删除 */
    public function del(int $api_id , MenuAuthApiRepository $menuAuthApiRepository)
    {
        $menuAuthApiRepository->del($api_id);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    public function list(MenuAuthApiRepository $menuAuthApiRepository)
    {

        ["total" => $total , "list" => $list] = $menuAuthApiRepository->list($this->params);

        return $this->outputList(
            total: $total,
            list: $list,
        );
    }

    /* 编辑 */
    public function edit(int $api_id , MenuAuthApiRepository $menuAuthApiRepository)
    {
        $res =  $menuAuthApiRepository->checkApi($this->params,$api_id);

        if ($res) return $this->outputEnum(Dev::API_EXIST, ["api_name" => $res->api_name]);

        $menuAuthApiRepository->edit($api_id , $this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    /* 是否添加日志 */
    public function addLog(int $api_id , MenuAuthApiRepository $menuAuthApiRepository)
    {
        $menuAuthApiRepository->edit($api_id , [ "add_log" => $this->params['add_log']]);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
