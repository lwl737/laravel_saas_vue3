<?php

declare(strict_types=1);

namespace App\Utils\Frequency;

enum Status: int
{
    case   success     = 1;      //成功
    case   ip_colling  = -1;    //ip冷却中
    case   ip_banned  = -2;    //ip封禁
}
