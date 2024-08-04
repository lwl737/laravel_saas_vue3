<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Helpers\Output\Json\Success;

use App\Dao\Mysql\Dev\MenuAuthButtonsDao\Orm as MenuAuthButtonsDao;

class MenuAuthButtons extends BaseController
{

    public function add()
    {
        MenuAuthButtonsDao::static()->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function del()
    {
        MenuAuthButtonsDao::static()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    public function edit()
    {
        MenuAuthButtonsDao::static()->edit($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
