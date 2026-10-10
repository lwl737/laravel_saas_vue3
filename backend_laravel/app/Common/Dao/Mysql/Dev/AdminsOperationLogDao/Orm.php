<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\AdminsOperationLogDao;

use App\Common\Models\Mysql\Orm\Dev\AdminsOperationLog;
use App\Common\Dao\Mysql\Dev\BaseDev;

class Orm extends BaseDev
{

    protected function getModel(): string
    {
        return AdminsOperationLog::class;
    }

    public function listConfig(array $params)
    {

        $this->query = $this->query->when(isset($params['log_id']), fn($query) => $query->where('admins_operation_log.log_id', (int)$params['log_id']))
            ->when(isset($params['log_id']), fn($query) => $query->where('admins_operation_log.log_id', (int)$params['log_id']))
            ->when(isset($params['menu_title']), fn($query) => $query->where('admins_operation_log.menu_title', "like", (string)$params['menu_title'] . "%"))
            ->when(isset($params['router_path']), fn($query) => $query->where('admins_operation_log.router_path', 'like', (string)$params['router_path'] . "%"))
            ->when(isset($params['api']), fn($query) => $query->where('admins_operation_log.api', "like", (string)$params['api'] . "%"))
            ->when(isset($params['operation_time']), fn($query) => $query->whereDate('admins_operation_log.operation_time', (string)$params['operation_time']))
            ->when(isset($params['auth_name']), fn($query) => $query->where('admins_operation_log.auth_name', "like", (string)$params['auth_name'] . "%"))
            ->when(isset($params['ip']), fn($query) => $query->where('admins_operation_log.ip', "like", (string)$params['ip'] . "%"))
            ->when(isset($params['nick_name']), fn($query) => $query->where('admins.nick_name', "like", (string)$params['nick_name'] . "%"))
            ->when(isset($params['realName']), fn($query) => $query->where('admins.realName', "like", (string)$params['realName'] . "%"));
        return $this;
    }
}
