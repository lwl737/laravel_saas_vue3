<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Helpers\Output\Json\Success;

use App\Common\Repository\Dev\SaasMenuAuthButtonsRepository;


class SaasMenuAuthButtons extends BaseController
{

    public function add(SaasMenuAuthButtonsRepository $menuAuthButtonsRepository)
    {
        $menuAuthButtonsRepository->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function del(int $buttons_id , SaasMenuAuthButtonsRepository $menuAuthButtonsRepository)
    {
        $menuAuthButtonsRepository->del($buttons_id);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    public function edit(int $buttons_id , SaasMenuAuthButtonsRepository $menuAuthButtonsRepository)
    {
        $menuAuthButtonsRepository->edit($buttons_id , $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
