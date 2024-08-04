<?php

declare(strict_types=1);
namespace App\Dao\Mysql\Dev\SaasMenuAuthApiDao;
use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\SaasMenuAuthApi;

class Orm extends BaseDev{

    public function __construct()
    {
        $this->query = new SaasMenuAuthApi;
    }

    public function checkApi(array $params){

        $this->query = $this->query->leftJoin("saas_menu_auth","saas_menu_auth.auth_id","=","saas_menu_auth_api.auth_id")->where('saas_menu_auth_api.menu_id' , $params['menu_id'])->where('saas_menu_auth_api.api',$params['api']);

        if(isset($params["api_id"]))  $this->query = $this->query->where('saas_menu_auth_api.api_id' , "<>" , $params["api_id"]);

        // return $this->query->first([DB::raw(" CASE WHEN `dev_menu_auth`.`auth_name` IS NULL THEN  `dev_menu_auth_api`.`api_name` ELSE `dev_menu_auth`.`auth_name` END AS api_name")]);

        $res = $this->query->first(["saas_menu_auth.auth_name" , "saas_menu_auth_api.api_name"]);

        if(!$res) return null;

        $res->api_name = $res->auth_name ? $res->auth_name : $res->api_name;

        return $res;
    }

    public function add(array $params)
    {
        $this->query->api = $params['api'];
        $this->query->menu_id = $params['menu_id'];
        $this->query->add_log = $params['add_log'];
        if(isset($params['api_name'])) $this->query->api_name = $params['api_name'];
        if(isset($params['auth_id'])) $this->query->auth_id = $params['auth_id'] ;
        $this->query->save();
    }

    public function listConfig(array $params){

        $this->query =  $this->query->where('auth_id',$params["auth_id"] ?? null);

        if(isset($params['menu_id'])) $this->query = $this->query->where('menu_id' , $params['menu_id'] );

        return $this;
    }


    public function del(array $params)
    {
       return $this->query->where('api_id',$params['api_id'])->delete();
    }

    public function edit(array $params)
    {

        return $this->query->where('api_id',$params['api_id'])->update(
            array_merge( [
                'api' => $params['api'],
                'add_log' => $params['add_log']
            ] , isset($params['api_name']) ? ["api_name" => $params['api_name']] : [] )
        );
    }

    public function addLog(array $params)
    {
        return $this->query->where('api_id',$params['api_id'])->update(['add_log' => $params['add_log']]) ;
    }
}
