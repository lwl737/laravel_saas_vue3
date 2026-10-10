<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas;

use App\Common\Repository\Saas\AdminsOperationLogRepository;

class OperationLog extends BaseController
{

    public function list(
        AdminsOperationLogRepository $adminsOperationLogRepository
    ) {

        ["total" => $total, "list" => $list] = $adminsOperationLogRepository->list($this->params, [
            'admins_operation_log.log_id',
            'admins_operation_log.auth_name',
            'admins_operation_log.ip',
            'admins_operation_log.menu_title',
            'admins_operation_log.operation_time',
            'admins.real_name'
        ]);

        return $this->outputList(
            total: $total,
            list: $list
        );
    }
}
