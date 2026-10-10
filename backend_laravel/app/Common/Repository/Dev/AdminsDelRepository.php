<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\AdminsDelDao\Orm as AdminsDelDao;

class AdminsDelRepository extends BaseRepository
{

    public function __construct(
        protected AdminsDelDao $dao
    ) {}


}
