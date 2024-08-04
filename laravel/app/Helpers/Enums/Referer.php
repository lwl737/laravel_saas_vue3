<?php

declare(strict_types=1);

namespace App\Helpers\Enums;
//内部状态码错误
enum Referer: string
{
    case  SAAS = "http://localhost:887|http://106.55.196.101:887";     //saas域名
    case  DEV = "http://localhost:886|http://106.55.196.101:886";     //开发者域名
}
