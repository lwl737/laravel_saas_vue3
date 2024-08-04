<?php

declare(strict_types=1);

namespace App\Utils\Frequency;

enum RedisConfig
{
    case   statistical_ip;      //统计ip访问次数 list解构
    case   wait_ip;    //请求频繁的ip
    case   statistical_today_ip;    //统计ip当天封禁次数
    case   banned_ip;    //禁止访问的ip


    /**
     * @description:
     * @param {string} $prefix id的前缀
     * @param {string} $ip     不传就代表不需要id
     * @return {*}
     */
    public function get(string  $prefix  = '', string  $ip = '')
    {
        $res = match ($this) {
            RedisConfig::statistical_ip => ['key' => 'STATISTICAL_IP', 'DB' => 1],
            RedisConfig::wait_ip =>   ['key' => 'WAIT_IP', 'DB' => 1],
            RedisConfig::statistical_today_ip => ['key' => 'STATISTICAL_TODAY_IP', 'DB' => 1],
            RedisConfig::banned_ip =>  ['key' => 'BANNED_IP', 'DB' => 1]
        };
        $res['id'] = $prefix;
        if ($ip !== '') $res['id'] =  $res['id'] .= $ip;
        return $res;
    }
}
