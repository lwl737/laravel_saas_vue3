<?php

declare(strict_types=1);

namespace App\Common\Models\Redis;

use Redis;

class RedisFunction
{
    public readonly string  $key;
    public readonly Redis  $redis;
    public function __construct(Redis $redis, int $db, string|null $key, int|string|null $id = null)
    {
        $this->redis = $redis;
        $this->redis->select($db);
        $this->key =   ($key === null ?  'none' : constant("\App\Common\Models\Redis\DB\DB{$db}::" . $key)->value) . ($id !== null ? $id : '');
    }

    public function getProperty()
    {
        return ['redis' => $this->redis, 'key' => $this->key];
    }
    //自增
    public function incr()
    {
        return  $this->redis->incr($this->key);
    }

    /**
     * @description: redis储存
     * @param  string , 储存值的字段
     * @param  string $val 储存值的字符串
     * @return bool   $res 保存是否成功
     */
    public function set($val, $time = false)
    {
        $res = $this->redis->set($this->key, $val);
        !$time ? '' :  self::expire($time); //限时
        return $res;
    }
    /**
     * @description: redis  获取
     * @param  string ,   字段
     * @return string $val  返回的值
     */
    public function get()
    {
        $val =  $this->redis->get($this->key);
        return $val;
    }

    /**
     * @description:  设定过期时间
     * @param string ,  索引
     * @param int    $time 过期时间
     * @return {*}
     */
    public function expire($time)
    {
        //, = $this->DbName.'_'.,;
        $this->redis->expire($this->key, $time);
    }


    /**
     * @description: 哈希获取所有值
     * @param  string , 索引
     * @return array
     */
    public function redishGetAll()
    {
        $val =    $this->redis->hGetAll($this->key);
        return $val;
    }
    /**
     * @description: 哈希删除所有元素
     * @param string , 索引
     * @return {*}
     */
    public function redishDel()
    {
        // $res =    $this->redis->hKeys($this->key);
        // if (!empty($res)) {
        //     for ($i = 0; $i < count($res); $i++) {
        //         $this->redis->hDel($res[$i]);
        //     }
        // }
    }
    /**
     * @description:  判断key是否存在
     * @param  string ,  索引
     * @return bool   $res  是否存在 true存在 false不存在
     */
    public function existSKey()
    {

        $res =   $this->redis->exists($this->key);

        return $res;
    }

    /**
     * @description: 获取单独的哈希字段
     * @param string 索引
     * @param string 字段
     * @return bool
     */
    public function hGET($field)
    {
        $res =   $this->redis->hGET($this->key, $field);
        return $res;
    }


    /**
     * @description:   修改哈希值
     * @param  string ,   钥匙
     * @param  array  $field 要修改的字段
     * @param  int    $time  设置过期时间
     * @return bool   $res
     */
    public function hMset($field, $time = null)
    {
        $res =   $this->redis->hMset($this->key, $field);
        !$time ?: self::expire($time);
        return $res;
    }

    /**
     * @description: 哈希多个字段一起拿取
     * @param  array  $field  字段名
     * @return mixed  $res    查询的信息
     */
    public function hMget($field)
    {
        $res =   $this->redis->hMget($this->key, $field);
        return $res;
    }
    /**
     * @description: 清处单条缓存
     * @param {*} ,
     * @return {*}
     */
    public function del()
    {
        $res =  $this->redis->del($this->key);
        return $res;
    }

    /**
     * @description:  判断缓存 哈希是否存在
     * @param  string ,  键值
     * @param  string $hkey 哈希键值
     * @return bool   $res
     */
    public function hExists($hKey)
    {
        $res =  $this->redis->hExists($this->key, $hKey);
        return $res;
    }

    public function reName($oldKey, $newKey)
    {
        // $oldKey = $this->DbName . '_' . $oldKey;
        // $newKey = $this->DbName . '_' . $newKey;
        // $res =  $this->redis->Rename($oldKey, $newKey);
        // return $res;
    }

    public function eval($luaScript, ...$arguments)
    {
        // $oldKey = $this->DbName . '_' . $oldKey;
        // $newKey = $this->DbName . '_' . $newKey;
        // $res =  $this->redis->Rename($oldKey, $newKey);
        // return $res;

        return $this->redis->eval($luaScript, $arguments);
    }


    /**
     * @description:  删除哈希键值
     * @param string ,  键值
     * @param string $hKey 哈希键值
     * @return bool   $res
     */
    public function hDel($hKey)
    {
        $res = $this->redis->hDel($this->key, $hKey);
        return $res;
    }


    //   不需要区分
    /**
     * @description: 统计当前库数据
     * @param {*}
     * @return {*}
     */
    public function dbsize()
    {
        $res = $this->redis->dbsize();
        return $res;
    }
    /**
     * @description: 查看队列长度
     * @param {*}
     * @return {*}
     */
    public function llen()
    {
        $res = $this->redis->llen($this->key);
        return $res;
    }
    public function rPop()
    {
        $res = $this->redis->rPop($this->key);
        return $res;
    }

    public function lindex($index)
    {
        $res = $this->redis->lindex($this->key, $index);
        return $res;
    }
    /**
     * @description: 队列 头部添加元素
     * @param {*}
     * @return {*}
     */
    public function lpush($value, $time = null)
    {
        if (is_array($value)) {
            array_unshift($value, $this->key);
            $res = call_user_func_array([$this->redis, 'lPush'], $value);
        } else {
            $res = $this->redis->lpush($this->key, $value);
        }
        !$time ?: self::expire($time);
        return $res;
    }
    /**
     * @description: 队列 尾部部添加元素
     * @param {*}
     * @return {*}
     */
    public function rpush($value, $time = null)
    {
        if (is_array($value)) {
            array_unshift($value, $this->key);
            $res = call_user_func_array([$this->redis, 'rpush'], $value);
        } else {
            $res = $this->redis->rpush($this->key, $value);
        }
        !$time ?: self::expire($time);

        return $res;
    }

    /**
     * @description: 判断集合内有的元素
     * @return {*}
     */
    public function  sismember($value)
    {
        return $this->redis->sismember($this->key, $value);
    }

    /**
     * @description: 添加客户集合
     * @param {*} ,
     * @return {*}
     */
    public function sAdd($value, $time = null)
    {
        if (is_array($value)) {
            array_unshift($value, $this->key);
            $res = call_user_func_array([$this->redis, 'sAdd'], $value);
        } else {
            $res =  $this->redis->sAdd($this->key, $value);
        }
        !$time ?: self::expire($time);
        return $res;
    }

    /**
     * @description: 移除并返回某一个元素
     * @param {*} ,
     * @return {*}
     */
    public function sPop()
    {

        $res =  $this->redis->sPop($this->key);

        return $res;
    }

    /**
     * @description: 移除集合内的元素
     * @param {*},
     * @return {*}
     */
    public function sRem(string|int|array $value)
    {

        if (is_array($value)) {
            array_unshift($value, $this->key);
            $res = call_user_func_array([$this->redis, 'sRem'], $value);
        } else {
            $res =  $this->redis->sRem($this->key, $value);
        }

        return $res;
    }


    public function sCard()
    {
        $res =  $this->redis->sCard($this->key);

        return $res;
    }

    /**
     * @description: 获取集合所有元素
     * @param {*} ,
     * @return {*}
     */
    public function smembers()
    {
        //, = $this->DbName.'_'.,;
        $res =  $this->redis->smembers($this->key);
        return $res;
    }

    /**
     * @description: 获取哈希所有键值
     * @param {*} ,
     * @return {*}
     */
    public function hKeys()
    {
        $res =  $this->redis->hKeys($this->key);
        return $res;
    }




    public function lRange($start, $end)
    {
        $res = $this->redis->lRange($this->key, $start, $end);
        return $res;
    }

    public function lTrim($start, $end)
    {
        $res = $this->redis->lTrim($this->key, $start, $end);
        return $res;
    }



    /**
     * @description:  移出并获取列表的第一个元素
     * @param {*} ,
     * @param {*} $timeout  等待超时时间  弹出
     * @return {*}
     */
    public function blpop($timeout = 0)
    {
        // $res = $this->redis->blpop($timeout);
        // return $res;
    }

    /**
     * @description: 清空所有缓存
     * @param {*}
     * @return {*}
     */
    public function flushAll()
    {
        $this->redis->flushAll();
    }


    /**
     * @description: 哈希自增并返回自增值
     * @return {*}
     */
    public function  hincrby($field, $int_num, $time = null)
    {
        $res = $this->redis->hincrby($this->key, $field, $int_num);
        !$time ?: self::expire($time);
        return $res;
    }

    public function  ttl()
    {
        $res = $this->redis->ttl($this->key);
        return $res;
    }

    public function  setNx($value, array $options = [])
    {
        $res = $this->redis->set($this->key, $value, array_merge($options, ['nx']));
        return $res;
    }

    public function  flushDb()
    {
        $this->redis->flushDB();
    }
}

class Myface
{
    public static $redis;

    public static function __callStatic($methodname, $arguments)
    {
        if (!self::$redis instanceof Connect) self::$redis = (new Connect)->redis;
        $classname = static::getFacadeClass();
        ['key' => $key, 'id' => $id, 'DB' => $db] = self::getData($arguments[count($arguments) - 1]);
        unset($arguments[count($arguments) - 1]);
        if ($classname)  $obj = new $classname(self::$redis, $db, $key, $id);
        return call_user_func_array([$obj, $methodname], $arguments);
    }

    private static function getData($checkData)
    {
        $key = $checkData['key'] ?? null;
        $id = $checkData['id'] ?? null;
        $DB = $checkData['DB'] ?? 0;
        return ["DB" => $DB, 'key' => $key, 'id' => $id];
    }


    public final static function create(array $arguments)
    {
        if (!self::$redis instanceof Connect) self::$redis = (new Connect)->redis;
        ['key' => $key, 'id' => $id, 'DB' => $db] = self::getData($arguments);
        return (new RedisFunction(self::$redis, $db, $key, $id))->getProperty();
    }
}

/**
 *  @see \App\Common\Models\Redis\RedisFunction
 *  @method static mixed redishGetAll()
 *  @method static mixed incr()
 *  @method static mixed set()
 *  @method static mixed get()
 *  @method static mixed expire()
 *  @method static mixed redishDel()
 *  @method static mixed existSKey()
 *  @method static mixed hGET()
 *  @method static mixed hMset()
 *  @method static mixed hMget()
 *  @method static mixed del()
 *  @method static mixed hExists()
 *  @method static mixed hDel()
 *  @method static mixed dbsize()
 *  @method static mixed llen()
 *  @method static mixed rPop()
 *  @method static mixed lindex()
 *  @method static mixed lpush()
 *  @method static mixed rpush()
 *  @method static mixed sismember()
 *  @method static mixed sAdd()
 *  @method static mixed sPop()
 *  @method static mixed sRem()
 *  @method static mixed sCard()
 *  @method static mixed smembers()
 *  @method static mixed hKeys()
 *  @method static mixed lRange()
 *  @method static mixed lTrim()
 *  @method static mixed blpop()
 *  @method static mixed flushAll()
 *  @method static mixed hincrby()
 *  @method static mixed ttl()
 *  @method static mixed flushDb()
 *  @method static mixed eval()
 */
class Control extends Myface
{
    public  static function  getFacadeClass()
    {
        return 'App\Common\Models\Redis\RedisFunction';
    }
}
