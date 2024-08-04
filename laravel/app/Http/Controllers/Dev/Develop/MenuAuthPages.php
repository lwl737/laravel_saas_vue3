<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Dao\Mysql\Dev\MenuDao\Orm as MenuDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;


/**
 * 后台管理
 */
class MenuAuthPages extends BaseController
{

    public function add()
    {
        $res =  MenuDao::static()->checkPath($this->params)->query()->first(['name', 'menu_id']);

        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->name, "menu_id" => $res->menu_id]);

        MenuDao::static()->add($this->params);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }

    public function del()
    {
        MenuDao::static()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    public function edit()
    {
        $res =  MenuDao::static()->checkPath($this->params)->query()->first(['name', 'menu_id']);

        if ($res)  return $this->outputEnum(Dev::MENU_EXIST, ['title' => $res->name, "menu_id" => $res->menu_id]);

        MenuDao::static()->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
