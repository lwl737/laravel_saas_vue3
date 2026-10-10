<?php

declare(strict_types=1);

namespace App\Common\Models\Redis\DB;

enum DB1: string
{
    case  STATISTICAL_IP  = 'STATISTICAL_IP_';    //统计ip访问次数 list解构
    case  WAIT_IP = 'WAIT_IP_';    //请求频繁的ip
    case  STATISTICAL_TODAY_IP = 'STATISTICAL_TODAY_IP';    //当天封禁次数
    case  BANNED_IP  = 'BANNED_IP';    //已经封禁的ip

    case  ES_KEY_IDX  = 'ES_KEY_IDX_';    //es IDX值
    case  ES_LOCAL  = 'ES_LOCAL_';    //es 锁
}
