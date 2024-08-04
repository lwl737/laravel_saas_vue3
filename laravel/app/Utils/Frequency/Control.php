<?php

declare(strict_types=1);

namespace App\Utils\Frequency;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Func;
use App\Models\Redis\Control as Redis;
use App\Helpers\Output\Json\Frequency;

class Control
{

    /**
     * @description:
     * @param  int     $request
     * @param  int     $next
     * @param  string  $redis_key
     * @param  int     $redis_key_db
     */
    public function __construct(
        public readonly int $second,
        public readonly int $num,
        public readonly string $prefix = '',
        public readonly array  $wait_time = [],
    ) {
        if ($this->num <= 0)  Func::throwHttpCustom(Frequency::NUM_ERROR);
        if ($this->second <= 0)  Func::throwHttpCustom(Frequency::SECOND_ERROR);
        if (!array_is_list($this->wait_time))  Func::throwHttpCustom(Frequency::WAIT_TIME_ERROR);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure|Status  $next
     */
    public function check(Request $request, Closure|null $next = null)
    {
        $now = time();

        $ip = Func::getRealIp($request);


        if ($this->checkBannedIp($ip))  return $next === null ? Status::ip_banned :  $next(Status::ip_banned, null);

        ['exist' => $exist, 'ttl' => $ttl] =  $this->checkWaitIp($ip);  //检查ip是否频繁请求


        if ($exist) return $next === null ? Status::ip_colling :  $next(Status::ip_colling, $ttl);   //请求频繁 解禁剩余时间


        $redis_config = RedisConfig::statistical_ip->get($this->prefix, $ip);

        $getTime =  Redis::lindex(- ($this->num - 1),  $redis_config);

        $status = Status::success;

        if ($getTime &&  $now -   $this->second  <= $getTime) {   //在限制的时间内 请求达到了某次数

            $num = $this->statisticalWaitIp($ip);

            //一天封禁超过3次 第四次时就永久封禁
            //小于三次叠加今天次数 ip请求频繁
            $num > count($this->wait_time) ?  $this->addBannedIp($ip) : $this->addWaitIp($ip, $num);

            $status = Status::ip_colling;
        } else {
            Redis::rpush($now, $this->second, $redis_config);  //叠加
            Redis::lTrim(-$this->num, -1, $redis_config);        //修剪
        }

        return  $next === null  ? $status :  $next($status);
    }




    /**
     * @description:
     * @return {*}
     */
    private function checkWaitIp(string $ip)
    {

        $redis_config = RedisConfig::wait_ip->get($this->prefix, $ip);

        $result = Redis::ttl($redis_config);   //检查key值是否存在和剩下的过期

        return match ($result) {
            -2 => ['exist' => false, 'ttl' => $result],    //不存在或者过期
            -1 => ['exist' => true, 'ttl' => $result],
            default => ['exist' => true, 'ttl' => $result]
        };
    }

    /**
     * @description:
     * @return {*}
     */
    private function addWaitIp(string $ip, int $num)
    {

        $redis_config =  RedisConfig::wait_ip->get($this->prefix, $ip);

        $expire_time =  $this->wait_time[$num - 1] ?? 0;

        Redis::set($num, $expire_time, $redis_config);
    }

    private function addBannedIp(string $ip)
    {

        $redis_config =  RedisConfig::banned_ip->get($this->prefix);

        return  Redis::hMset([$ip => 1], $redis_config);   //设置封禁ip


    }

    /**
     * @description:
     * @return {*}
     */
    private function checkBannedIp(string $ip)
    {

        $redis_config =  RedisConfig::banned_ip->get($this->prefix);

        return Redis::hExists($ip, $redis_config);
    }


    /**
     * @description:   统计ip当天封禁次数
     * @param {string} $ip
     * @return {*}
     */
    private function statisticalWaitIp(string $ip): int
    {

        $redis_config = RedisConfig::statistical_today_ip->get($this->prefix);

        $addExpire =  Redis::existSKey($redis_config) ? true : false;

        $num = Redis::hincrby($ip, 1,   $addExpire ?  Func::afterDaysTimestamp(1) - time() : null, $redis_config);

        return (int)$num;
    }
}
