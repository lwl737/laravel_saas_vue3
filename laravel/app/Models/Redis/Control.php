<?php

declare(strict_types=1);

namespace App\Models\Redis;

use Redis;

class RedisFunction
{
    public readonly string  $key;
    public readonly Redis  $redis;
    public function __construct(Redis $redis, int $db, string|null $key, int|string|null $id = null)
    {
        $this->redis = $redis;
        $this->redis->select($db);
        $this->key =   ($key === null ?  'none' : constant("\App\Models\Redis\DB\DB{$db}::" . $key)->value) . ($id !== null ? $id : '');
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
     * @param  string ,
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
        $checkData = $arguments[count($arguments) - 1];
        $key = $checkData['key'] ?? null;
        $id = $checkData['id'] ?? null;
        $DB = $checkData['DB'] ?? 0;
        unset($arguments[count($arguments) - 1]);
        if ($classname)  $obj = new $classname(self::$redis, $DB, $key, $id);
        return call_user_func_array([$obj, $methodname], $arguments);
    }


    public final static function create(int $db, string $key, string|int|null $id = null)
    {
        if (!self::$redis instanceof Connect) self::$redis = (new Connect)->redis;
        return new RedisFunction(self::$redis, $db, $key, $id);
    }
}

/**
 *  @see \App\Models\Redis\RedisFunction
 *  @method static redishGetAll()
 *  @method static incr()
 *  @method static set()
 *  @method static get()
 *  @method static expire()
 *  @method static redishDel()
 *  @method static existSKey()
 *  @method static hGET()
 *  @method static hMset()
 *  @method static hMget()
 *  @method static del()
 *  @method static hExists()
 *  @method static hDel()
 *  @method static dbsize()
 *  @method static llen()
 *  @method static rPop()
 *  @method static lindex()
 *  @method static lpush()
 *  @method static rpush()
 *  @method static sismember()
 *  @method static sAdd()
 *  @method static sPop()
 *  @method static sRem()
 *  @method static sCard()
 *  @method static smembers()
 *  @method static hKeys()
 *  @method static lRange()
 *  @method static lTrim()
 *  @method static blpop()
 *  @method static flushAll()
 *  @method static hincrby()
 *  @method static ttl()
 *  @method static flushDb()
 */
class Control extends Myface
{
    public  static function  getFacadeClass()
    {
        return 'App\Models\Redis\RedisFunction';
    }
}
