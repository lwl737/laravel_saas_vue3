<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Common\Repository\Dev\AdminsOperationLogRepository;

class OperationLog extends BaseController
{
    public function list(AdminsOperationLogRepository $repository)
    {
        ["total" => $total , "list" => $list] = $repository->list($this->params);

        return $this->outputList(
            total: $total,
            list: $list
        );
    }
}
