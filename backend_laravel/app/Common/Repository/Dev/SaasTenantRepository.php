<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\SaasTenantDao\Orm as SaasTenantDao;
use App\Helpers\Func;
use App\Helpers\Queue\Modules\Rabbitmq;

class SaasTenantRepository extends BaseRepository
{

    public function __construct(
        protected SaasTenantDao $dao
    ) {}


    public function list(array $params)
    {
        $dao = $this->dao->listConfig($params);
        return [
            'total' => $dao->query()->count(),
            'list' => $dao->list($params, 'desc', fn($query) => $query->orderby('tenant_sort', 'desc'))->query()->get([
                "tenant_id",
                "tenant_name",
                "tenant_sort",
                "created_time",
                "status",
            ])->map(function ($item) {
                $item->tenant_url = Func::tenantUrl((string)$item->tenant_id);
                return $item;
            })->makeVisible(['created_time'])
        ];
    }

    public function add(array $params)
    {

        $tenant_id = $this->dao->saveOrgani([
            "tenant_name" => $params["tenant_name"],
            "status" => $params["status"],
            "tenant_sort" => $params["tenant_sort"]
        ]);

        Rabbitmq::TENANT_CREATE->run([
            "tenant_id" => $tenant_id
        ]);
    }

    public function edit(int $tenant_id, array $params)
    {
        $this->dao->edit($tenant_id, [
            "tenant_name" => $params["tenant_name"],
            "tenant_sort" => $params["tenant_sort"],
            "status" => $params["status"],
        ]);
    }


    public function editStatus(int $tenant_id, array $params){
        $this->dao->edit($tenant_id, [
            "status" => $params["status"],
        ]);
    }


    public function all(){
        return $this->dao->all();
    }
}
