<?php

declare(strict_types=1);

namespace App\Utils\Uuid;

use Ramsey\Uuid\Uuid;

class Control
{
    public static function createVersion4()
    {
        $uuid = Uuid::uuid4();
        return  $uuid->toString();
    }
}
