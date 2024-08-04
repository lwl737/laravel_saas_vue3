<?php

declare(strict_types=1);

namespace App\Jobs;


use App\Models\Mysql\Orm\Saas\AdminsOperationLog;
use App\Dao\Mysql\Dev\SaasMenuRelationDao\Orm as MenuRelationDao;
use App\Helpers\Func;
use App\Services\Tenant\Server;
class SaasAdminOperation extends Job
{





    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        [
            "sql" => $sql,
            "params" => $params,
            "insert_admins_id" => $insert_admins_id,
            "insert_organi_id" => $insert_organi_id,
            "tenant_id" => $tenant_id,
            "router_path" => $router_path,
            "api" => $api,
            "auth_name" => $auth_name,
            "sql" => $sql,
            "ip" => $ip,
            "menu_id" => $menu_id,
            "enum" => $enum,
            "method" => $method,
            "operation_time" => $operation_time
        ] = $this->getData();

        Server::tenancyInitialize($tenant_id);
        $menu_title = MenuRelationDao::static()->getAncestorLinkTitle($menu_id)?->menu_title;
        $menu_title = $menu_title ?  $menu_title : "" ;
        $model = new AdminsOperationLog;
        $model->sql = $sql;
        $model->params = $params;
        $model->insert_admins_id = $insert_admins_id;
        $model->insert_organi_id = $insert_organi_id;
        $model->api =  $api;
        $model->auth_name =  $auth_name;
        $model->router_path = $router_path;
        $model->ip = $ip;
        $model->operation_time = $operation_time;
        $model->menu_title = Func::strOmit( $menu_title ?  $menu_title : "" , 255 ) ;
        $model->menu_title_full = $menu_title  ;
        $model->enum = $enum;
        $model->method = $method;
        $model->save();
    }
}
