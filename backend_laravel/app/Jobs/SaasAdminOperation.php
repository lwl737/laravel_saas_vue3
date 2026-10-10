<?php

declare(strict_types=1);

namespace App\Jobs;


use App\Common\Repository\Saas\AdminsOperationLogRepository;
use App\Common\Repository\Dev\SaasMenuRelationRepository;
use App\Helpers\Func;
use App\Services\Tenant\Server;

class SaasAdminOperation extends Job
{





    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        AdminsOperationLogRepository $adminsOperationLogRepository,
        SaasMenuRelationRepository $saasMenuRelationRepository,
    ) {

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
        $menu_title = $saasMenuRelationRepository->getAncestorLinkTitle($menu_id)?->menu_title;
        $menu_title = $menu_title ?  $menu_title : "";
        $adminsOperationLogRepository->add([
            "sql" =>  $sql,
            "params" =>  $params,
            "insert_admins_id" =>  $insert_admins_id,
            "insert_organi_id" =>  $insert_organi_id,
            "api" =>  $api,
            "auth_name" =>  $auth_name,
            "router_path" =>  $router_path,
            "ip" =>  $ip,
            "operation_time" =>  $operation_time,
            "menu_title" =>   Func::strOmit($menu_title ?  $menu_title : "", 255),
            "menu_title_full" =>   $menu_title,
            "enum" =>   $enum,
            "method" =>   $method,
        ]);
    }
}
