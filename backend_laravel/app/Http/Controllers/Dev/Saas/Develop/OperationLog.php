<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Dao\Mysql\Saas\AdminsOperationLogDao\Orm;
use App\Common\Repository\Saas\AdminsOperationLogRepository;


class OperationLog extends BaseController
{
    public function list(
        AdminsOperationLogRepository $adminsOperationLogRepository
    )
    {
        ["total" => $total , "list" => $list]  =$adminsOperationLogRepository->list($this->params , [
            'admins_operation_log.*',
            'admins.real_name'
        ]);

        return $this->outputList(
            total: $total,
            list: $list
        );
    }

}
