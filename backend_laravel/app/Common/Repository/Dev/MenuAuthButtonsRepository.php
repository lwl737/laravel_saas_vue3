<?php

declare(strict_types=1);

namespace App\Common\Repository\Dev;

use App\Common\Dao\Mysql\Dev\MenuAuthButtonsDao\Orm as MenuAuthButtonsDao;

class MenuAuthButtonsRepository extends BaseRepository
{
    public function __construct(
        protected MenuAuthButtonsDao $dao
    ) {}

}
