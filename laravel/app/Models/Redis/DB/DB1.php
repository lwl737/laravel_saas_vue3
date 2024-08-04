<?php

declare(strict_types=1);

namespace App\Models\Redis\DB;

enum DB1: string
{
    case  STATISTICAL_IP  = 'STATISTICAL_IP_';    //统计ip访问次数 list解构
    case  WAIT_IP = 'WAIT_IP_';    //请求频繁的ip
    case  STATISTICAL_TODAY_IP = 'STATISTICAL_TODAY_IP';    //当天封禁次数
    case  BANNED_IP  = 'BANNED_IP';    //已经封禁的ip
}
