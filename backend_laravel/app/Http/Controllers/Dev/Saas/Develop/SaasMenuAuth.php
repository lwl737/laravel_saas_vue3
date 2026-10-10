<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Helpers\Output\Json\Success;
use App\Common\Repository\Dev\SaasMenuAuthRepository;

class SaasMenuAuth extends BaseController
{

    /* 添加 */
    public function add(SaasMenuAuthRepository $menuAuthRepository)
    {
        $menuAuthRepository->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    /* 列表 */
    public function list(SaasMenuAuthRepository $menuAuthRepository)
    {
        ["total" => $total, "list" => $list] = $menuAuthRepository->list($this->params);

        return  $this->outputList(
            total: $total,
            list: $list,
        );
    }
    /* 编辑 */
    public function edit(int $auth_id, SaasMenuAuthRepository $menuAuthRepository)
    {

        $menuAuthRepository->edit($auth_id, $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    /* 删除 */
    public function del(int $auth_id, SaasMenuAuthRepository $menuAuthRepository)
    {
        $menuAuthRepository->del($auth_id);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }
}
