<?php

declare(strict_types=1);
namespace App\Common\Dao\Mysql\Dev\SaasMenuAuthDao;
use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Models\Mysql\Orm\Dev\SaasMenuAuth;
class Orm extends BaseDev{

    protected function getModel(): string
    {
        return SaasMenuAuth::class;
    }


    public function whereConfig($params)
    {
        $this->query = $this->query->when( isset($params["menu_id"]),function($query) use ($params){
            $query->where('menu_id',$params["menu_id"]);
        })->when(isset($params["auth_ids"]) &&  is_array($params["auth_ids"]) , function($query) use ($params){
            $query->whereIn('auth_id',$params["auth_ids"]);
        });
        return $this;
    }


    public function listOrderBy(array $params){
        $this->query =  $this->query
        ->offset(($params["pageNum"] - 1) * $params["pageSize"])->limit($params["pageSize"])
        ->orderBy('auth_sort','desc')
        ->orderBy('auth_id','desc');
        return  $this;
    }


    public function withAll()
    {

        $this->query = $this->query->with(['buttons','api','pages']);

        return $this;
    }




}
