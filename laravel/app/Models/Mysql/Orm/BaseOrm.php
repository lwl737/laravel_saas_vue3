<?php

declare(strict_types=1);

namespace App\Models\Mysql\Orm;

use Illuminate\Support\Facades\DB;
use App\Helpers\Func;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;

// use GeneaLabs\LaravelModelCaching\Traits\Cachable;


abstract class BaseOrm extends Model
{
    // use Cachable;  //orm所有数据进缓存
    public $table;
    public $primaryKey;
    public readonly string $pre;
    public readonly string $tableName;

    public function __construct()
    {

        $class = explode('\\', get_class($this));  //获取子级类名

        $this->table =  Func::toUnderScore(array_pop($class));  //转下划线 表名

        $table_data =  explode('_', $this->table);

        $this->primaryKey =  $this->primaryKey ?  $this->primaryKey : end($table_data) . '_id';   //id

        $this->pre = isset($this->connection) ? config('database.connections.' . $this->connection . '.prefix') : "";

        $this->hidden = array_merge([self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT], $this->hidden);

        $this->tableName =  $this->table;

        parent::__construct();  //避免软删除查询不到
    }


    const CREATED_AT = 'created_time';
    const UPDATED_AT = 'updated_time';
    const DELETED_AT = 'deleted_time';

    public $timestamps = true;

    /**
     * @description:  批量添加
     * @param {*} $data
     * @return {*}
     */
    public function insertAll(array $data, $field = [], \Closure|null $func = null)
    {
        $insertStr = '';
        $timestamps = $this->timestamps  ? date('Y-m-d H:i:s') : null;
        for ($i = 0; $i < count($data); $i++) {

            if ($timestamps) {
                $data[$i][self::CREATED_AT] =  $timestamps;
                $data[$i][self::UPDATED_AT] =  $timestamps;
            }

            $data[$i] = $func === null  ? $data[$i] : $func($data[$i] , $i);
            if ($i == 0) {
                $insertStr = 'INSERT INTO `' . $this->pre . $this->table . '` (  ';
                $arr_keys  = array_keys($data[$i]);
                foreach ($arr_keys as $val) {
                    if (!empty($field) && !in_array($val, $field)) continue;
                    $insertStr .= '`' . $val . '`,';
                }
                $insertStr = substr($insertStr, 0, -1);
                $insertStr .=  ' ) VALUES ';
            }

            $insertStr .= '(';
            foreach ($data[$i] as $key => $val) {
                if ((!empty($field) && !in_array($key, $field))) continue;
                $insertStr .= "'$val',";
            }
            $insertStr = substr($insertStr, 0, -1);
            $insertStr .= '),';
        }
        $insertStr = substr($insertStr, 0, -1);
        return  DB::connection($this->connection)->insert($insertStr);
    }


    public function  insertUpdatedStr($array, array $insertFiled = [], array $updateFiled = [])
    {
        $keyStr = '';
        $valStr = '';
        $updateStr = '';
        $time = date('Y-m-d H:i:s');

        foreach ($array as $key => $val) {
            $keyStr .= '`' . $key . '`,';
            $valStr .= '"' . $val . '",';
            $updateStr .= '`' . $key . '`= "' . $val . '",';
        }
        foreach ($insertFiled as $key => $val) {
            $keyStr .= '`' . $key . '`,';
            $valStr .= '"' . $val . '",';
        }

        foreach ($updateFiled as $key => $val)  $updateStr .= '`' . $key . '`= "' . $val . '",';


        $keyStr .=  '`' . $this::CREATED_AT . '`';                             //创建时间
        $valStr .=  '"' . $time . '"';                                         //创建时间
        $updateStr .= '`' . $this::UPDATED_AT . '`= "' . $time . '"';          //修改时间
        return   DB::connection($this->connection)->update("INSERT INTO " . $this->pre . $this->table . " ( " . $keyStr . ") VALUES ( " . $valStr . ") ON DUPLICATE KEY UPDATE " . $updateStr);
    }


    public function whereKw(array $kw = [])
    {

        $kw = empty($kw) ?  Request::input('kw', []) : $kw;
        $orm = $this;
        if (!empty($kw)) {
            foreach ($kw as $key => $val) {
                if (is_array($val) && isset($val['type']) && isset($val['value'])) {
                    if ($val['value'] === null || $val['value'] === '') continue;
                    switch ($val['type']) {
                        case 'date':
                            $orm =  $orm->whereDate($key, $val['value']);
                            break;
                        default:
                            $orm =  $orm->where($key, ...$this->mysqlSymbol($val['type'], $val['value']));
                            break;
                    }
                } else {
                    if ($val === null || $val === '') continue;
                    $orm = $orm->where($key, 'LIKE',  $val . '%');
                }
            }
        }
        return  $orm;
    }


    /**
     * @description: mysql符号
     * @param  string $op   操作
     * @param  string $val  值
     * @return array
     */
    private function mysqlSymbol(string $op, int|string $val): array
    {
        switch ($op) {
            case 'like':
                return ['LIKE',  $val . "%"];
            case 'greaterEq':
                return  ['>=',  $val];
            case 'lessEq':
                return  ['<=',  $val];
                // case 'between':
                //   list($left, $right) =  explode(',', $val);
                //   return ' between ' . $left . ' AND  ' . $right . ' ';
                // case 'notIn':
                //   return ' NOT  IN  (' . $val . ') ';
                // case 'in':
                //   return '   IN  (' . $val . ') ';
            case 'equal':
            default:
                return  ['=',  $val];
        }
    }


    public function getKeyValue(string $label, string|null $default = null)
    {

        $key = isset( $this->$label ) ? $label : $default;
        return $key ? $this->$key : null;
    }




    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getPrimaryKeyVal()
    {
        $primaryKey = $this->getPrimaryKey();
        //获得自增的id
        return $this->$primaryKey;
    }

    protected static function boot()
    {
        parent::boot();
    }

    public function  getInsertOrganiNameAttribute(){
        if( $this->attributes['insert_organi_name'] ) return $this->attributes['insert_organi_name'];
        return  $this->attributes['insert_organi_id'] === 0 ?  Func::getAllOrganiName() : "部门已删除";
    }

    public function getCreatedTimeAttribute($value)
    {
        return $value  === null ?  null : date('Y-m-d H:i:s', strtotime($value) );
    }
}
