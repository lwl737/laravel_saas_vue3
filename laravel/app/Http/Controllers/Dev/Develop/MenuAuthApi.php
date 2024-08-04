<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Dao\Mysql\Dev\MenuAuthApiDao\Orm as MenuAuthApiDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Json\Dev;

class MenuAuthApi extends BaseController
{


    public function add()
    {
        $res =  MenuAuthApiDao::static()->checkApi($this->params);

        if($res) return $this->outputEnum(Dev::API_EXIST,["api_name" => $res->api_name]);

        MenuAuthApiDao::static()->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 删除 */
    public function del()
    {
        MenuAuthApiDao::static()->del($this->params);

        return $this->outputEnum(Success::DEL_SUCCESS);
    }

    /* 编辑 */
    public function edit()
    {
        $res =  MenuAuthApiDao::static()->checkApi($this->params);

        if($res) return $this->outputEnum(Dev::API_EXIST,["api_name" => $res->api_name]);

        MenuAuthApiDao::static()->edit($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    /* 是否添加日志 */
    public function addLog()
    {
        MenuAuthApiDao::static()->addLog($this->params);

        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

}
