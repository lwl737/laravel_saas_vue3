<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;
use App\Common\Repository\Dev\SaasMenuRepository;

/**
 * 后台管理
 */
class SaasMenuAuthPages extends BaseController
{

    public function add(SaasMenuRepository $menuRepository)
    {
        $res =  $menuRepository->checkPath($this->params, ["title", "menu_id"]);

        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->title, "menu_id" => $res->menu_id]);

        $menuRepository->addPages($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function del(int $menu_id, SaasMenuRepository $menuRepository)
    {
        $menuRepository->del($menu_id);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    public function edit(int $menu_id, SaasMenuRepository $menuRepository)
    {
        $res =  $menuRepository->checkPath($this->params , ['title', 'menu_id'] , $menu_id);

        if ($res) return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->title, "menu_id" => $res->menu_id]);

        $menuRepository->edit($menu_id , $this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
