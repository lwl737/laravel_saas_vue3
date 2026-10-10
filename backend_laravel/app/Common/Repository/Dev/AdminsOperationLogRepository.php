<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\AdminsOperationLogDao\Orm as AdminsOperationLogDao;

class AdminsOperationLogRepository extends BaseRepository
{

    public function __construct(
        protected AdminsOperationLogDao $dao
    ) {}

    public function list(array $params)
    {
        $dao = $this->dao->listConfig($params);


        return [
            'total' => $dao->query()->count(),
            'list' => $dao->list($params)->listOrganiConfig($params)->listOrganiGet([
                'admins_operation_log.*',
                'insert_admins.real_name'
            ])
        ];
    }
}
