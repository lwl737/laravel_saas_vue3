<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Dev\SaasMenuAuthButtonsDao;

use App\Common\Dao\Mysql\Dev\BaseDev;
use App\Common\Models\Mysql\Orm\Dev\SaasMenuAuthButtons;

class Orm extends BaseDev
{

    protected function getModel(): string
    {
        return SaasMenuAuthButtons::class;
    }
}
