<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas;
use App\Dao\Mysql\Dev\SaasTenantDao\Orm as SaasTenantDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Queue\Modules\Rabbitmq;
use App\Helpers\Func;
class Tenant extends BaseController
{

    public function list()
    {
        $dao = SaasTenantDao::static()->listConfig($this->params);

        return $this->outputList(
            total:$dao->query()->count(),
            list: $dao->list($this->params,'desc',fn($query)=>$query->orderby('tenant_sort','desc'))->query()->get([
                "tenant_id",
                "tenant_name",
                "tenant_sort",
                "created_time",
                "status",
            ])->map(function($item){
                $item->tenant_url = Func::tenantUrl( (string)$item->tenant_id )  ;
                return $item;
            })->makeVisible(['created_time'])
        );
    }
    public function add()
    {
        $tenant_id = SaasTenantDao::static()->add($this->params);
        Rabbitmq::TENANT_CREATE->run([
            "tenant_id" => $tenant_id
        ]);
        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    public function edit()
    {
        SaasTenantDao::static()->edit($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    public function editStatus()
    {
        SaasTenantDao::static()->editStatus($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    public function del()
    {
        SaasTenantDao::static()->del($this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    public function all()
    {
        return $this->output(
            ["all" => SaasTenantDao::static()->all()],
            Success::SEL_SUCCESS
        );
    }
}
