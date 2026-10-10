<?php

declare(strict_types=1);

namespace App\Jobs;


use App\Helpers\Func;
use App\Common\Repository\Dev\MenuRelationRepository;
use App\Common\Repository\Dev\AdminsOperationLogRepository;
use App\Jobs\DevAdminOperationFailed;
class DevAdminOperation extends Job
{



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        MenuRelationRepository $menuRelationRepository,
        AdminsOperationLogRepository $adminsOperationLogRepository,
    )
    {
        [
            "sql" => $sql,
            "params" => $params,
            "insert_admins_id" => $insert_admins_id,
            "insert_organi_id" => $insert_organi_id,
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
        $menu_title = $menuRelationRepository->getAncestorLinkTitle($menu_id)?->menu_title;
        $menu_title = $menu_title ?  $menu_title : "" ;
        $adminsOperationLogRepository->add([
            "sql" => $sql,
            "params" => $params,
            "insert_admins_id" => $insert_admins_id,
            "insert_organi_id" => $insert_organi_id,
            "api" => $api,
            "auth_name" => $auth_name,
            "router_path" => $router_path,
            "ip" => $ip,
            "operation_time" => $operation_time,
            "menu_title" => Func::strOmit( $menu_title ?  $menu_title : "" , 255 ) ,
            "menu_title_full" =>  $menu_title ,
            "enum" =>  $enum ,
            "method" =>  $method ,
        ]);
    }
}
