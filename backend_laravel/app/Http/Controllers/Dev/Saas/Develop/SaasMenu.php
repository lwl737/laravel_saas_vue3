<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;

use App\Common\Repository\Dev\SaasMenuRepository;
use App\Common\Repository\Dev\SaasMenuRelationRepository;

class SaasMenu extends BaseController
{



    /* 添加 */
    public function add(SaasMenuRepository $menuRepository, SaasMenuRelationRepository $menuRelationRepository)
    {
        $res =  $menuRepository->checkPath($this->params, ["title", "menu_id"]);
        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->title, "menu_id" => $res->menu_id]);
        // 祖先link
        ["max_level" => $max_level, "ancestor_link" => $ancestor_link, "level_link" => $level_link] = $menuRelationRepository->getAncestorLink(
            $this->params
        );
        $menuRepository->add($this->params, (int)$max_level, (array)$ancestor_link, $level_link);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 列表 */
    public function all(SaasMenuRepository $menuRepository)
    {
        return $this->output($menuRepository->tree(), Success::SEL_SUCCESS);
    }
    /* 删除 */
    public function del(int $menu_id, SaasMenuRepository $menuRepository)
    {
        $menuRepository->del($menu_id);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }
    /* 编辑 */
    public function edit(int $menu_id, SaasMenuRepository $menuRepository)
    {
        $res = $menuRepository->checkPath(["path" => $this->params["path"]], ["title", "menu_id"] , $menu_id);

        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->title, "menu_id" => $res->menu_id]);

        $menuRepository->edit($menu_id, $this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
