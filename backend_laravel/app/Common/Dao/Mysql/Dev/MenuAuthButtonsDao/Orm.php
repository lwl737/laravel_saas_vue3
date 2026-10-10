<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\MenuAuthButtonsDao;

use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Models\Mysql\Orm\Dev\MenuAuthButtons;

class Orm extends BaseDev
{

    protected function getModel(): string
    {
        return MenuAuthButtons::class;
    }
}
