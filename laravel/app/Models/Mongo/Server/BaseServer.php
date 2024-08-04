<?php

declare(strict_types=1);

namespace App\Models\Mongo\Server;

use App\Helpers\Func;
use App\Models\Redis\Control as RedisControl;
use App\Helpers\Queue\QueueInterface;
use App\Helpers\Queue\Modules\Rabbitmq;

use App\Models\Mongo\Handle;

abstract class BaseServer
{

    protected static string $database = '';

    protected static string $table = '';

    protected static array  $index = [];  //索引

    protected static QueueInterface|null $queue = null;


    final public static function create(int|string|float|null $tableAfter = '',  int|string $dbAfter = ''): Handle
    {

        // var_dump($tableAfter);
        // var_dump($dbAfter);

        $obj = self::createBase($tableAfter, $dbAfter);   //创建MONGODB 对象

        if ($obj instanceof \MongoDB\Collection) {  //文档对象

            $key =  $obj->getCollectionName();

            $config = self::getConfig($obj);

            $result =  RedisControl::hGET($key, $config);

            $serializeIdx  = serialize(static::$index);

            //索引发生变化时
            if (!$result  || $result !== $serializeIdx) {   // 大于一条数据的时候才建立索引
                // dispatch((new MongoIndexReset(static::class, $tableAfter, $dbAfter))->onConnection('rabbitmq')->onQueue('mongo_index'));
                Rabbitmq::MONGO_INDEX->run(
                    [
                        "mongoServerName" =>static::class ,
                        "tableAfter" => $tableAfter ,
                        "dbAfter" => $dbAfter
                    ]
                );
            }
        }



        return $obj;
    }

    /**
     * 根据首字母划分表
     *
     * @param string $keyWord  字符串
     * @param string $dbAfter  数据库
     */
    public static function createFirst(string $keyWord, int|string $dbAfter = ''): Handle
    {
        return  self::create(substr($keyWord, 0, 1), $dbAfter);
    }


    /**
     * 根据数字区间划分表
     *
     * @param string $increment  当前数值
     * @param string $range      范围
     * @param string $dbAfter    数据库
     */
    public static function createRange(int $increment, int $range, int|string $dbAfter = ''): Handle
    {
        return  self::create(ceil($increment / $range) * $range, $dbAfter);
    }


    /**
     * 根据日期划分表
     *
     * @param int    $time     时间戳
     * @param string $format   格式化数据
     * @param string $dbAfter  数据库
     */
    public static function createDate(int $time, string $format = 'y-m-d', int|string $dbAfter = ''):Handle
    {
        return  self::create(date($format, $time), $dbAfter);
    }


    // \MongoDB\Collection|\MongoDB\Database
    final public static function createBase(
        int|string|float|null $tableAfter = '',
        int|string $dbAfter = ''
    ): Handle
    {

        if (!static::$database) {
            $arr = explode('\\', static::class);
            $database  =   Func::toUnderScore(end($arr));
        } else $database  =   static::$database;


        if ($dbAfter !== '')  $database = $database . '_' . $dbAfter;

        $table = null;
        if ($tableAfter === '') $table = static::$table;
        else if ($tableAfter !== null)  $table = static::$table . (static::$table ===  '' ?  $tableAfter : '_' . $tableAfter);

        return  new Handle(
            // !isset($table) ? static::connect()->$database  : static::connect()->$database->$table ,
            $database,
            $table,
            static::$queue
        );
    }


    final public static function getConfig(\MongoDB\Collection $obj)
    {
        return ['key' => 'MONGODB_INDEX', 'id' =>  $obj->getDatabaseName()];
    }


    /**
     * @description:    重置所有索引操作
     * @param {null} $tableAfter
     * @param {string} $dbAfter
     * @return {*}
     */
    final public static function resetIndex(int|string|float|null $tableAfter = '',  int|string $dbAfter = '')
    {

        $collection = self::createBase($tableAfter, $dbAfter);   //创建MONGODB 对象

        if ($collection instanceof \MongoDB\Collection) {  //文档对象

            $key =  $collection->getCollectionName();

            $config = self::getConfig($collection);

            $result =  RedisControl::hGET($key, $config);

            $serializeIdx  = serialize(static::$index);

            //索引发生变化时
            if ((!$result || $result !== $serializeIdx)  &&  $collection->countDocuments() > 0) {   // 大于一条数据的时候才建立索引

                $collection->dropIndexes();                             //删除所有索引

                foreach (static::$index as $val) {

                    if (is_array($val)) {
                        if (array_is_list($val)) {
                            ksort($val[0]);
                            $collection->createIndex($val[0], $val[1] ?? []);
                        } else {
                            ksort($val);
                            $collection->createIndex($val);
                        }
                    }
                }

                RedisControl::hMset([$key => $serializeIdx], $config);   //设置下次进来时

            }
        }
    }


    public function getDatabase(){
        return static::$database;
    }
}
