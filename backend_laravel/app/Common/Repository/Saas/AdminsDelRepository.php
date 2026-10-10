<?php

declare(strict_types=1);

namespace App\Common\Repository\Saas;

use App\Common\Dao\Mysql\Saas\AdminsDelDao\Orm as AdminsDelDao;

class AdminsDelRepository extends BaseRepository
{

    public function __construct(
        protected AdminsDelDao $dao
    ) {}


}
