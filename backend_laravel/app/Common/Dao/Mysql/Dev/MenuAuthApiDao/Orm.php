<?php

declare(strict_types=1);
namespace App\Common\Dao\Mysql\Dev\MenuAuthApiDao;
use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Models\Mysql\Orm\Dev\MenuAuthApi;
// use Illuminate\Support\Facades\DB;

class Orm extends BaseDev{

    protected function getModel(): string
    {
        return MenuAuthApi::class;
    }

    public function checkApi(array $params ,int|null $api_id = null){

        $this->query = $this->query->leftJoin("menu_auth","menu_auth.auth_id","=","menu_auth_api.auth_id")->where('menu_auth_api.menu_id' , $params['menu_id'])->where('menu_auth_api.api',$params['api']);

        if($api_id)  $this->query = $this->query->where('menu_auth_api.api_id' , "<>" , $api_id);

        return $this->query->first(["menu_auth.auth_name" , "menu_auth_api.api_name"]);
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
