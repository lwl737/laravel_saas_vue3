<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas\Develop;

use App\Dao\Mysql\Saas\AdminsOperationLogDao\Orm;

class OperationLog extends BaseController
{
    public function list()
    {
        $dao = Orm::static([$this->params['tenant_id']])->listConfig($this->params);

        return $this->outputList(
            total: $dao->query()->count(),
            list: $dao->list($this->params)->listOrganiGet([
                'admins_operation_log.*',
                'admins.real_name'
            ])->toArray()
        );
    }

}
