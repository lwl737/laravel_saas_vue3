<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\AdminsDelDao;

use App\Common\Models\Mysql\Orm\Dev\AdminsDel;
use App\Common\Dao\Mysql\Dev\BaseDev;

class Orm extends BaseDev
{
    protected function getModel(): string
    {
        return AdminsDel::class;
    }


}
