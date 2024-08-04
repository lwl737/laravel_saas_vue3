<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Dao\Mysql\Dev\SaasMenuDao\Orm as MenuDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;


/**
 * 后台管理
 */
class SaasMenuAuthPages extends BaseController
{

    public function add()
    {
        $res =  MenuDao::static()->checkPath($this->params)->query()->first(['title']);

        if ($res)  return $this->outputEnum(Dev::PAGES_IS_EXIST, ['menu_title' => $res->title]);

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
        $res =  MenuDao::static()->checkPath($this->params)->query()->first(['title']);

        if ($res)  return $this->outputEnum(Dev::PAGES_IS_EXIST, ['menu_title' => $res->title]);

        MenuDao::static()->edit($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
}
