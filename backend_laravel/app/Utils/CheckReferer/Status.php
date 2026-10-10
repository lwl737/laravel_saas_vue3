<?php

declare(strict_types=1);

namespace App\Utils\CheckReferer;

enum Status: int
{
    case SUCCESS = 0;
    case NOT_DEFIENED = 1;
    case ERROR = 2;
}
