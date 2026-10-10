<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Saas\AdminsDelDao;

use App\Common\Models\Mysql\Orm\Saas\AdminsDel;
use App\Common\Dao\Mysql\Saas\BaseSaas;

class Orm extends BaseSaas
{
    protected function getModel(): string
    {
        return AdminsDel::class;
    }


}
