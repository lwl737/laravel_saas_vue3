<?php

declare(strict_types=1);

namespace App\Common\Models\Mysql\Trait\Enums;

enum Status:int {
    case ENABLE = 1;
    case FORBIDDEN = 0;
}
