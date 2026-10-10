<?php

declare(strict_types=1);

namespace App\Common\Repository\Saas;

use App\Common\Dao\Mysql\Saas\AdminsOperationLogDao\Orm as AdminsOperationLogDao;

class AdminsOperationLogRepository extends BaseRepository
{

    public function __construct(
        protected AdminsOperationLogDao $dao
    ) {}

    public function list(array $params , array $field  )
    {
        $dao = $this->dao->tenancyInitialize($params["tenant_id"])->listOrganiGetWhere($params)->listConfig($params);

        return [
            'total' => $dao->query()->count(),
            'list' => $dao->list($params)->listOrganiGet($field)
        ];
    }


}
