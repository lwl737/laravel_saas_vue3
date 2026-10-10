<?php

declare(strict_types=1);

namespace App\Common\Models\Redis;

use App\Common\Models\Redis\Control;

class RedisMutexLock
{

    public function __construct(
        public readonly array $redisData,
        public readonly string|int $_id,
        public readonly  int $acquireTimeout = 2  //超时两秒
    ) {}


    /**
     * 获取分布式锁（加锁）
     * @param lockKey 锁key
     * @param _id 客户端请求标识
     * @param expireTime 超期时间,毫秒，默认15s
     * @param isNegtive 是否是悲观锁，默认否
     * @return bool 是否获取成功
     */
    public  function tryGetDistributedLock($expireTime = 15, $isNegtive = true): bool
    {
        if ($isNegtive) { //悲观锁
            /**
             * 悲观锁 循环阻塞式锁取，阻塞时间为元素acquireTimeout
             */
            $endtime = microtime(true) * 1000 + $this->acquireTimeout * 1000;
            while (microtime(true) * 1000 < $endtime) { //每隔一段时间尝试获取一次锁
                $acquired = Control::setNx($this->_id, ['ex' => $expireTime], $this->redisData);
                if ($acquired) { //获取锁成功，返回true
                    return true;
                }
                usleep(100);
            }
            //获取锁超时，返回false
            return false;
        } else { //乐观锁
            /**
             * 乐观锁只尝试一次，成功返回true,失败返回false
             */
            $ret = Control::setNx($this->_id, ['ex' => $expireTime], $this->redisData);
            if ($ret) {
                return true;
            }
            return false;
        }
    }

    /**
     * 解锁
     * @param $lockKey 锁key
     * @param $_id 客户端请求唯一标识
     */
    public function releaseDistributedLock()
    {
        $luaScript = <<<EOF
if redis.call("get",ARGV[1]) == ARGV[2]
then
    return redis.call("del",ARGV[1])
else
    return 0
end
EOF;

        ["redis" => $redis, "key" => $key] = Control::create($this->redisData);

        $res =  $redis->eval($luaScript, [$key, $this->_id]);

        if ($res) {
            return true;
        }
        return false;
    }
}
