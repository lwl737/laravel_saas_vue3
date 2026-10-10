<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\SaasMenuAuthButtonsDao\Orm as SaasMenuAuthButtonsDao;

class SaasMenuAuthButtonsRepository extends BaseRepository
{
    public function __construct(
        protected SaasMenuAuthButtonsDao $dao
    ) {}

}
