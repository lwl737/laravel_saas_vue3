<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev;


use App\Dao\Mysql\Dev\AdminsOperationLogDao\Orm;
use App\Common\Repository\Dev\AdminsOperationLogRepository;

class OperationLog extends BaseController
{

    public function list(AdminsOperationLogRepository $adminsOperationLogRepository)
    {

        ["list" => $list, "total" => $total] = $adminsOperationLogRepository->organiScope()->list($this->params);

        return $this->outputList(
            total: $total,
            list: $list
        );
    }
}
