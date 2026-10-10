<?php

declare(strict_types=1);
namespace App\Common\Dao\Mysql\Dev\SaasMenuAuthApiDao;
use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Models\Mysql\Orm\Dev\SaasMenuAuthApi;
// use Illuminate\Support\Facades\DB;

class Orm extends BaseDev{

    protected function getModel(): string
    {
        return SaasMenuAuthApi::class;
    }

    public function checkApi(array $params ,int|null $api_id = null){

        $this->query = $this->query->leftJoin("saas_menu_auth","saas_menu_auth.auth_id","=","saas_menu_auth_api.auth_id")->where('saas_menu_auth_api.menu_id' , $params['menu_id'])->where('saas_menu_auth_api.api',$params['api']);

        if($api_id)  $this->query = $this->query->where('saas_menu_auth_api.api_id' , "<>" , $api_id);

        return $this->query->first(["saas_menu_auth.auth_name" , "saas_menu_auth_api.api_name"]);
    }

    public function listConfig(array $params){

        $this->query =  $this->query->where('auth_id',$params["auth_id"] ?? null);

        if(isset($params['menu_id'])) $this->query = $this->query->where('menu_id' , $params['menu_id'] );

        return $this;
    }

    public function addLog(array $params)
    {
        return $this->query->where('api_id',$params['api_id'])->update(['add_log' => $params['add_log']]) ;
    }
}
