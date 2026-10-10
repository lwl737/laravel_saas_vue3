<?php

declare(strict_types=1);
namespace App\Common\Dao\Mongo;
use App\Common\Dao\BaseDao;
use App\Helpers\Func;
use App\Helpers\Output\Json\Error;
use App\Common\Models\Mongo\Handle;

abstract class BaseMongo extends BaseDao{

    public  Handle|null $mongo = null;

    public function __call($name, $arguments)
    {
        if(!$this->mongo instanceof Handle) Func::throwHttpCustom(Error::MONGO_NOT_INIT);
        return call_user_func_array([$this->mongo, $name], $arguments);
    }

    public function page(array $data){
        return [
            "skip" => (int)(($data['pageNum'] - 1) * $data['pageSize']),
            "limit" => (int)$data['pageSize']
        ];
    }

      /**
     * 根据首字母划分表
     *
     * @param string $keyWord  字符串
     * @param string $dbAfter  数据库
     */
    public final function createFirst(string $keyWord, int|string $dbAfter = '')
    {
        $this->mongo = $this->getModel()::create(substr($keyWord, 0, 1), $dbAfter);
        return  $this;
    }


    /**
     * 根据数字区间划分表
     *
     * @param string $increment  当前数值
     * @param string $range      范围
     * @param string $dbAfter    数据库
     */
    public final function createRange(int $increment, int $range, int|string $dbAfter = '')
    {
        $this->mongo =  $this->getModel()::create(ceil($increment / $range) * $range, $dbAfter);
        return  $this;
    }


    /**
     * 根据日期划分表
     *
     * @param int    $time     时间戳
     * @param string $format   格式化数据
     * @param string $dbAfter  数据库
     */
    public final function createDate(int $time, string $format = 'y-m-d', int|string $dbAfter = '')
    {
        $this->mongo =  $this->getModel()::create(date($format, $time), $dbAfter);
        return  $this;
    }



    /**
     * @description:      查找所有集合
     * @param {array} $data
     * @return {*}
     */
    public final function getTableName()
    {
        $data = [];
        foreach ($this->getModel()::create(null)->listCollectionNames() as $val) $data[] = $val;
        return $data;
    }


    public final function getCollectionName(){
       return $this->mongo->getCollectionName();
    }


}
