<?php

declare(strict_types=1);

namespace App\Http\Controllers\Saas;


use App\Dao\Mysql\Saas\AdminsOperationLogDao\Orm;

class OperationLog extends BaseController
{

    public function list()
    {
        $dao = Orm::static()->organiScope()->listConfig($this->params);

        return $this->outputList(
            total: $dao->query()->count(),
            list: $dao->list($this->params)->listOrganiGet([
                'admins_operation_log.log_id',
                'admins_operation_log.auth_name',
                'admins_operation_log.ip',
                'admins_operation_log.menu_title',
                'admins_operation_log.operation_time',
                'admins.real_name'
            ])->toArray()
        );
    }
}
