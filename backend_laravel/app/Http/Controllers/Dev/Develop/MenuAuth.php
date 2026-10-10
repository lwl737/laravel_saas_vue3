<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Helpers\Output\Json\Success;
use App\Common\Repository\Dev\MenuAuthRepository;

class MenuAuth extends BaseController
{

    /* 添加 */
    public function add(MenuAuthRepository $menuAuthRepository)
    {
        $menuAuthRepository->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /* 列表 */
    public function list(MenuAuthRepository $menuAuthRepository)
    {
        ["total" => $total, "list" => $list] = $menuAuthRepository->list($this->params);

        return  $this->outputList(
            total: $total,
            list: $list,
        );
    }
    /* 编辑 */
    public function edit(int $auth_id, MenuAuthRepository $menuAuthRepository)
    {

        $menuAuthRepository->edit($auth_id, $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    /* 删除 */
    public function del(int $auth_id, MenuAuthRepository $menuAuthRepository)
    {
        $menuAuthRepository->del($auth_id);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }
}
