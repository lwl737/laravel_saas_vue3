<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Dao\Mysql\Dev\AdminsOperationLogDao\Orm;

class OperationLog extends BaseController
{
    public function list()
    {
        $dao = Orm::static()->listConfig($this->params);

        return $this->outputList(
            total: $dao->query()->count(),
            list: $dao->list($this->params)->listOrganiGet([
                'admins_operation_log.*',
                'admins.real_name'
            ])->toArray()
        );
    }
}
