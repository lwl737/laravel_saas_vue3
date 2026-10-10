<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql;

use Illuminate\Support\Facades\DB;
use App\Common\Models\Mysql\Orm\BaseOrm;
use Illuminate\Database\Eloquent\Builder;
use Closure;
use App\Common\Dao\BaseDao;

abstract class BaseMysql extends BaseDao
{

    private bool $isLeftJoinOrgani = false;
    private bool $isOrganiScope = false;
    public function __construct()
    {
        $model =  new ($this->getModel());
        $this->query = $model;
        $this->model = $model;
    }

    /** @var Builder|BaseOrm|null **/
    protected  $query = null;
    protected  BaseOrm|null  $model = null;

    protected final function setIsLeftJoinOrgani(bool $bool)
    {
        $this->isLeftJoinOrgani = $bool;
        return $this;
    }

    public final function query(): Builder|BaseOrm|null
    {
        return $this->query;
    }

    public final function initialize()
    {
        $this->query = new ($this->getModel());
        return $this;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->query, $name], $arguments);
    }

    public final function  listOrganiGet(array|null $field = null)
    {

        if (!$field) $field = [$this->model->tableName . ".*"];
        //所属部门 添加人
        $field = array_merge($field, [
            'organi.organi_name as insert_organi_name',
            $this->model->tableName . ".insert_organi_id",
            'insert_admins.nick_name as insert_nick_name'
        ]);

        $this->leftJoinOrgani();
        //添加部门字段
        return $this->query->get($field)->makeHidden(["insert_organi_id", "insert_admins_id"]);
    }



    public final function pages(int $pageNum, int $pageSize)
    {
        $this->query = $this->query->offset(($pageNum - 1) * $pageSize)->limit($pageSize);
        return $this;
    }

    public final  function orderBy(string $sort = "desc", Closure|null $beforeFunc = null)
    {


        $table_name =  $this->getTableName();

        $primaryKey = $this->getPrimaryKey();

        if ($beforeFunc) $this->query = $beforeFunc($this->query);

        $this->query = $this->query->orderBy($table_name . "." . $primaryKey, $sort);

        return $this;
    }

    public final function getTableName()
    {
        return $this->model->getTable();
    }

    public final function getPrimaryKey()
    {
        return $this->model->getPrimaryKey();
    }


    public final static function transaction(Closure $func)
    {
        DB::transaction(fn() => $func());
    }

    public final  function disableCache()
    {
        $this->query = $this->query->disableCache();
        return $this;
    }


    public final function list(array $params, string $sort = "desc", Closure|null $beforeFunc = null)
    {
        if ($beforeFunc) $this->query = $beforeFunc($this->query);
        return  $this->orderby($sort)->pages((int)$params["pageNum"], (int)$params["pageSize"]);
    }


    public final function whereStatusEnable()
    {
        $this->query = $this->query->where($this->model->getStatusKey(), $this->model->getStatusVal("ENABLE"));
        return $this;
    }


    public final function dbRawfindInSet(array $data, string $filed, string $wh = "OR"): string
    {
        $str = "";
        foreach ($data as $val) $str .= " FIND_IN_SET({$val},`{$filed}`) {$wh}";
        if ($str) $str = substr($str, 0, -mb_strlen($wh));
        return $str;
    }


    public function add(array $params)
    {
        $query = clone  $this->model;
        foreach ($params as $key => $val)  $query->$key = $val;
        $primary_key = $this->getPrimaryKey();
        $query->save();
        //添加完返回主键
        return  $query->$primary_key;
    }

    public function edit(string|int $primary_key_val, array $params, Closure|null $whereFunc = null, bool $extend_query = false)
    {
        $query =  $extend_query ? clone $this->query : clone  $this->model;
        $query = $query->where($this->castsMixedData($this->getPrimaryKey(),$extend_query), $primary_key_val);
        if ($whereFunc) $query = $query->where($whereFunc);
        //返回影响条数
        return $query->update($this->castsMixedData($params,$extend_query));
    }


    public function editSave(string|int $primary_key_val, array $params)
    {
        $query = clone  $this->model;
        foreach ($params as $key => $val)  $query->$key = $val;
        $primary_key = $this->getPrimaryKey();
        $query->$primary_key = $primary_key_val;
        $query->save();
        //添加完返回主键
        return  $primary_key_val;
    }


    public function del(array|int|string $primary_key_val, Closure|null $whereFunc = null, bool $extend_query = false)
    {
        $query = $extend_query ? clone $this->query : clone  $this->model;
        if (is_array($primary_key_val)) {
            $query = $query->whereIn($this->castsMixedData($this->getPrimaryKey() , $extend_query), $primary_key_val);
        } else {
            $query = $query->where($this->castsMixedData($this->getPrimaryKey() , $extend_query), $primary_key_val);
        }

        if ($whereFunc) $query = $query->where($whereFunc);

        return $query->delete();
    }


    public final function checkExist(array $params, string|int|null $primary_val = null, bool $extend_query = false): bool
    {
        $query = $extend_query ? clone $this->query : clone  $this->model;

        foreach ($params as $key => $val) {
            $query = $query->where($this->castsMixedData($key,$extend_query), $val);
        }

        if ($primary_val) $query = $query->where( $this->castsMixedData( $this->getPrimaryKey() , $extend_query), "<>", $primary_val);

        return $query->exists();
    }

    /**
     * 转换数据
     *
     * @param array|string $params
     * @param boolean $extend_query
     * @return string|array
     */
    private  function castsMixedData(array|string $params, bool $extend_query): string|array
    {

        if ($this->isOrganiScope && $extend_query) {
            $tableName = $this->getTableName();

            if (is_string($params))   return $tableName . "." . $params;
            else {
                $newParams = [];

                foreach ($params as $key => $val) {
                    $newParams[$tableName . "." . $key] = $val;
                }
                return $newParams;
            }
        }
        return $params;
    }


    public final function editEnumStatus(int|string $primary_val, int|string $status, bool $extend_query = false)
    {
        $primaryKey = $this->castsMixedData($this->getPrimaryKey(), $extend_query);
        $statusKey = $this->castsMixedData($this->model->getStatusKey(), $extend_query);
        return ($extend_query ? clone $this->query : clone $this->model)->where($primaryKey,  $primary_val)->update([
            $statusKey => $status
        ]);
    }


    public final function getById(int|string|array $primary_val, array|null $field = null, bool $extend_query = false)
    {
        if (is_array($primary_val)) return ($extend_query ? clone $this->query : clone $this->model)->whereIn($this->castsMixedData($this->getPrimaryKey(),$extend_query), $primary_val)->get($field === null ? [$this->castsMixedData("*",$extend_query)] : $this->castsMixedData($field , $extend_query));
        else return ($extend_query ? clone $this->query : clone $this->model)->where($this->castsMixedData($this->getPrimaryKey(),$extend_query), $primary_val)->first($field === null ? [$this->castsMixedData("*",$extend_query)] : $this->castsMixedData($field,$extend_query));
    }

    public final function getByCount(int|string|array $primary_val, bool $extend_query = false)
    {
        if (is_array($primary_val)) return ($extend_query ? clone $this->query : clone $this->model)->whereIn($this->castsMixedData($this->getPrimaryKey(),$extend_query), $primary_val)->count();
        else return ($extend_query ? clone $this->query : clone $this->model)->where($this->castsMixedData($this->getPrimaryKey(),$extend_query), $primary_val)->count();
    }





    public final function organiScope()
    {
        if ($this->isOrganiScope) return $this;
        $this->query = $this->query->organi();
        $this->isOrganiScope = true;
        return $this;
    }

    public final function listOrganiConfig(array $params = [])
    {
        $this->query = $this->organiScope()->listOrganiGetWhere($params)->query();
        return $this;
    }

    public final function listOrganiGetWhere(array $params)
    {
        $this->query =  $this->leftJoinOrgani()->query()->when(array_key_exists("insert_nick_name", $params), function ($query) use ($params) {
            $query->where("insert_admins.nick_name", "like", $params["insert_nick_name"] . "%");
        })->when(array_key_exists('insert_organi_name', $params), function ($query) use ($params) {
            $query->where("organi.organi_name", "like", $params["insert_organi_name"] . "%");
        })->when(array_key_exists("insert_organi_id", $params), function ($query) use ($params) {
            $query->whereIn($this->model->tableName . ".insert_organi_id", $params["insert_organi_id"]);
        });
        return $this;
    }

    public final function leftJoinOrgani()
    {
        if ($this->isLeftJoinOrgani) return $this;
        $this->query = $this->query->leftJoin("organi", "organi.organi_id", "=", $this->model->tableName . "." . $this->model->getConst("INSERT_ORGANI_ID_KEY"))
            ->leftJoin("admins as insert_admins", "insert_admins.admins_id", "=", $this->model->tableName . "." . $this->model->getConst("INSERT_ADMINS_ID_KEY"));
        $this->isLeftJoinOrgani = true;
        return $this;
    }

    public final function saveOrgani(array $params)
    {
        $query = clone  $this->model;
        foreach ($params as $key => $val)  $query->$key = $val;
        $primary_key = $this->getPrimaryKey();
        $query->saveOrgani();
        //添加完返回主键
        return  $query->$primary_key;
    }
    public final function delOrgani(array|int|string $primary_key_val, Closure|null $whereFunc = null)
    {
        return $this->organiScope()->del($primary_key_val, $whereFunc, true);
    }

    public final function editOrgani(string|int $primary_key_val, array $params, Closure|null $whereFunc = null)
    {
        return $this->organiScope()->edit($primary_key_val, $params, $whereFunc, true);
    }

    public final function  organiGet(array|null $field = null)
    {

        if (!$field) $field = [$this->model->tableName . ".*"];
        //所属部门 添加人
        $field = array_merge($field, [
            'organi.organi_name as insert_organi_name',
            $this->model->tableName . ".insert_organi_id",
            'insert_admins.nick_name as insert_nick_name'
        ]);

        $this->leftJoinOrgani();
        //添加部门字段
        return $this->query->get($field)->makeHidden(["insert_organi_id", "insert_admins_id"]);
    }
}
