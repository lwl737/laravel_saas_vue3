<?php

declare(strict_types=1);

namespace App\Utils\Settings;

use App\Helpers\Func;
use App\Models\Mysql\Orm\BaseOrm;
class Control
{

    public function __construct(
        public readonly string $enum,
        public readonly BaseOrm $orm,
        public readonly array $config = [],
    ) {
    }

    public  function getItem(array|string $field) :array|string|int
    {

        $key = is_string($field) ? [$field] : $field;

        $qeury =  $this->orm->query();

        if(!empty( $this->config ) )  $qeury = $qeury->where($this->config);

        /* redis没有存入查mysql */
        $result =  $qeury->first()?->toArray();

        /* *mysql没有查到查询配置默认参数 并加入mysql */
        if (empty($result)) $result = $this->setDefault();


        if (!empty($key)) {
            $data = [];
            foreach ($key as $val) {
                //一些已经加了mysql 一些没加 (添加配置)
                if (!isset($result[$val]) || $result[$val] === null) $data[$val] = constant("{$this->enum}::{$val}")->default();
                else  $data[$val] =  Func::casts(constant("{$this->enum}::{$val}")->casts(), $result[$val], true);
            }
            $result = $data;
        }
        return   is_string($field) ? $result[$field] : $result;
    }

    private function setDefault()
    {
        $result =  $this->enum::all();
        $this->orm->insert(array_merge( $result , $this->config ));
        return  $result;
    }


    public  function setItem(array $params): int
    {
        foreach ($params as $key => $val) $params[$key] = Func::casts(constant("{$this->enum}::{$key}")->casts(), $val); //数据转换成所需格式

        if (!$this->orm->exists()){
            return (int)$this->orm->insert(array_merge($this->enum::all(),$params,$this->config ));
        }else{
            return empty( $this->config ) ?  $this->orm->query()->update( $params ) : $this->orm->query()->where($this->config)->update( $params ) ;
        }

    }
}
