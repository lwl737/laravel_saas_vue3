<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Helpers\Output\Json\Success;

use App\Common\Repository\Dev\MenuAuthButtonsRepository;


class MenuAuthButtons extends BaseController
{

    public function add(MenuAuthButtonsRepository $menuAuthButtonsRepository)
    {
        $menuAuthButtonsRepository->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function del(int $buttons_id , MenuAuthButtonsRepository $menuAuthButtonsRepository)
    {
        $menuAuthButtonsRepository->del($buttons_id);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    public function edit(int $buttons_id , MenuAuthButtonsRepository $menuAuthButtonsRepository)
    {
        $menuAuthButtonsRepository->edit($buttons_id , $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
