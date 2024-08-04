<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Services\Dev\{AdminsLoginInfo, Permissions};
use App\Dao\Mongo\ErrorLogDao;
use App\Dao\Mysql\Dev\AdminsOperationLogDao\Orm;
use App\Helpers\Output\Json\Success;

class ErrorLog extends BaseController
{
    /* 错误日志 */
    public function list()
    {

        $dao = ErrorLogDao::static( [strtotime($this->params['ymd'])] );

        return $this->outputList(
            total: $dao->total(),
            list : $dao->list($this->params)
         );
    }


    public function date()
    {
        return $this->output([
            "date" => ErrorLogDao::getTableName()
        ], Success::SEL_SUCCESS);
    }
}
