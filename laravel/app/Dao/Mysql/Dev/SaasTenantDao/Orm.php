<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Dev\SaasTenantDao;

use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\SaasTenant;
use App\Models\Mysql\Trait\Enums\Status;
use App\Dao\Mysql\Dev\SaasTenantDao\Enums\TenantEnable;
class Orm extends BaseDev
{

    public function __construct()
    {
        $this->query = new SaasTenant;
    }

    public function checkTenantEnable(array $params):SaasTenant|TenantEnable{

        $res = $this->query->where("tenant_id", $params["tenant_id"])->first([
            'status',
            // 'role_json',
            // 'role_id',
        ]);

        if(!$res) return TenantEnable::NOT_EXIST;

        if(!$res->isEnable()) return TenantEnable::STATUS_NOT_ENABLE;

        return $res;
    }
    public function listConfig(array $params)
    {

        if (isset($params["tenant_name"])) $this->query = $this->query->where("tenant_name", "like", $params["tenant_name"] . "%");

        return $this;
    }

    public function add(array $params):int
    {

        $this->query->tenant_name = $params["tenant_name"];
        $this->query->status = $params["status"];
        $this->query->tenant_sort = $params["tenant_sort"];
        $this->query->saveOrgani();
        return $this->query->tenant_id;
    }

    public function edit(array $params)
    {

        return  $this->query->where("tenant_id", $params["tenant_id"])->update([
            "tenant_name" => $params["tenant_name"],
            "tenant_sort" => $params["tenant_sort"],
            "status" => $params["status"],
        ]);
    }

    public function editStatus(array $params){

        return $this->query->where("tenant_id", $params["tenant_id"])->update([
            "status" => $params["status"],
        ]);
    }

    public function editCreating(array $params){
        return $this->query->where("tenant_id", $params["tenant_id"])->update([
            "creating" => $params["creating"],
        ]);
    }


    public  function del(array $params){
        return $this->query->whereIn("tenant_id", $params["ids"])->delete();
    }


    public function all(){
        return $this->query
        ->where($this->model->getStatusKey() , Status::ENABLE->value)
        ->orderBy('tenant_sort' , 'desc')
        ->orderBy('tenant_id','desc')
        ->get([
            'tenant_id' ,
            'tenant_name'
        ])->toArray();
    }

}
