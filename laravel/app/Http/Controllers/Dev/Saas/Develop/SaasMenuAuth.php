<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Dao\Mysql\Dev\SaasMenuAuthDao\Orm as MenuAuthDao;
use App\Helpers\Output\Json\Success;

class SaasMenuAuth extends BaseController
{

    /* 添加 */
    public function add()
    {
        MenuAuthDao::static()->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    /* 列表 */
    public function list()
    {
        $dao = MenuAuthDao::static()->listConfig($this->params);

        return  $this->outputList(
            total: $dao->query()->count(),
            list: $dao->withAll()->listOrderBy($this->params)->query()->get(),
        );
    }
    /* 编辑 */
    public function edit()
    {

        MenuAuthDao::static()->edit($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    /* 删除 */
    public function del()
    {
        MenuAuthDao::static()->del($this->params);
        return $this->outputEnum(Success::DEL_SUCCESS);
    }
}
