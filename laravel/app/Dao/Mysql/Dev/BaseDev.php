<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Dev;
use  App\Dao\Mysql\BaseMysql;

abstract class BaseDev extends BaseMysql{

    private $isLeftJoinOrgani = false;
    private $isOrganiScope = false;
    // 取消部门条件
    public final function organiScope()
    {
        if($this->isOrganiScope) return $this;
        $this->query = $this->query->organi();
        $this->isOrganiScope = true;
        return $this;
    }

    public final function listOrganiConfig(array $params = [] ){

        $this->organiScope()->leftJoinOrgani();

        if(isset($params["insert_nick_name"])) $this->query = $this->query->where("admins.nick_name","like",$params["insert_nick_name"] . "%");

        if(isset($params["insert_organi_name"])) $this->query = $this->query->where("organi.organi_name","like",$params["insert_organi_name"] . "%");

        if(isset($params["insert_organi_id"])) $this->query = $this->query->whereIn($this->model->tableName.".insert_organi_id", $params["insert_organi_id"] );

        return $this;
    }

    public final function leftJoinOrgani(){
        if($this->isLeftJoinOrgani) return $this;
        $this->query = $this->query->leftJoin("organi","organi.organi_id", "=" , $this->model->tableName.".".$this->model::INSERT_ORGANI_ID_KEY  )
        ->leftJoin("admins","admins.admins_id", "=" , $this->model->tableName.".".$this->model::INSERT_ADMINS_ID_KEY);
        $this->isLeftJoinOrgani = true;
        return $this;
    }

    public final function  listOrganiGet(array|null $field = null){

        if(!$field) $field = [$this->model->tableName.".*"];
        //所属部门 添加人
        $field = array_merge($field,[
            'organi.organi_name as insert_organi_name'  ,
            $this->model->tableName.".insert_organi_id",
            'admins.nick_name as insert_nick_name'
        ]);

        $this->leftJoinOrgani();
        //添加部门字段
        return $this->query->get($field)->makeHidden(["insert_organi_id","insert_admins_id"]);
    }



}
