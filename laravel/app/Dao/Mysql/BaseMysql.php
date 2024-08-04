<?php

declare(strict_types=1);

namespace App\Dao\Mysql;

use Illuminate\Support\Facades\DB;
use App\Models\Mysql\Orm\BaseOrm;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Closure;
use App\Dao\BaseDao;
abstract class BaseMysql extends BaseDao
{


    private  Builder|BaseOrm|Collection|null  $query = null;
    private  BaseOrm|null  $model = null;

    public final function query():Builder|BaseOrm|Collection
    {
        return $this->query;
    }


    public function __set($name, $value)
    {

        if($name === "query" ) {
            if($this->model === null || $this->query === null ){
                $this->model =  $value;
                $this->query =  $value;
            }else{
                $this->query =  $value;
            }
        }


    }
    public function __get($name)
    {
        if($name === "query" || $name === "model"){
            return  $this->$name;
        }
    }

    public  function pages(int $pageNum,int $pageSize)
    {
        $this->query = $this->query->offset(($pageNum - 1) * $pageSize)->limit($pageSize);
        return $this;
    }

    public  function orderBy(string $sort = "desc" , Closure|null $beforeFunc = null  )
    {


        $table_name =  $this->getTableName();

        $primaryKey = $this->getPrimaryKey();

        if($beforeFunc) $this->query = $beforeFunc($this->query);

        $this->query = $this->query->orderBy( $table_name.".".$primaryKey , $sort);

        return $this;
    }

    public final function getTableName(){
        return $this->query instanceof Builder ? $this->query->getModel()->table : $this->query->table;
    }

    public final function getPrimaryKey(){
        return $this->query instanceof Builder ? $this->query->getModel()->getPrimaryKey() : $this->query->getPrimaryKey();
    }


    public final static function transaction(Closure $func)
    {
        DB::transaction(fn () => $func());
    }

    public final  function disableCache()
    {
        $this->query = $this->query->disableCache();
        return $this;
    }


    public function list(array $params ,string $sort = "desc" , Closure|null $beforeFunc = null  ){
        if($beforeFunc) $this->query = $beforeFunc($this->query);
        return  $this->orderby($sort)->pages((int)$params["pageNum"] , (int)$params["pageSize"]);
    }


    public function whereStatusEnable(){
         $this->query = $this->query->where($this->model->getStatusKey() , $this->model->getStatusVal("ENABLE"));
         return $this;
    }


}
