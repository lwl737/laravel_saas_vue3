<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Saas\AdminsOperationLogDao;

use App\Common\Models\Mysql\Orm\Saas\AdminsOperationLog;
use App\Common\Dao\Mysql\Saas\BaseSaas;
// use App\Services\Tenant\Server;

class Orm extends BaseSaas
{
    protected function getModel(): string
    {
        return AdminsOperationLog::class;
    }

    public function listConfig(array $params)
    {

        $this->query =  $this->query
        ->leftJoin('admins', 'admins.admins_id', '=', 'admins_operation_log.insert_admins_id')
        ->when(isset($params['log_id']), function ($query) use ($params) {
            $query->where('admins_operation_log.log_id', (int)$params['log_id']);
        })->when(isset($params['menu_title']), function ($query) use ($params) {
            $query->where('admins_operation_log.menu_title', 'like', (string)$params['menu_title'] . "%");
        })->when(isset($params['router_path']), function ($query) use ($params) {
            $query->where('admins_operation_log.router_path', 'like', (string)$params['router_path'] . "%");
        })->when(isset($params['api']), function ($query) use ($params) {
            $query->where('admins_operation_log.api', "like", (string)$params['api'] . "%");
        })->when(isset($params['operation_time']), function ($query) use ($params) {
            $query->whereDate('admins_operation_log.operation_time', (string)$params['operation_time']);
        })->when(isset($params["auth_name"]), function ($query) use ($params) {
            $query->where('admins_operation_log.auth_name', "like", (string)$params['auth_name'] . "%");
        })->when(isset($params['ip']) , function($query) use ($params){
            $query->where("admins_operation_log.ip", "like", (string)$params['ip'] . "%");
        })->when(isset($params['nickName']) , function($query) use ($params){
            $query->where("admins.nick_name", "like", (string)$params['nickName'] . "%");
        })->when(isset($params['realName']) , function($query) use ($params){
            $query->where("admins.real_name", "like", (string)$params['realName'] . "%");
        });

        return $this;
    }
}
