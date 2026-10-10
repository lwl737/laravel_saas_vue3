<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Develop;

use App\Common\Repository\Systems\ErrorLogRepository;
use App\Helpers\Output\Json\Success;

class ErrorLog extends BaseController
{
    /* 错误日志 */
    public function list(ErrorLogRepository $errorLogRepository)
    {
        ["total" => $total, "list" => $list] = $errorLogRepository->list($this->params);
        return $this->outputList(
            total: $total,
            list: $list
        );
    }


    public function date(ErrorLogRepository $errorLogRepository)
    {
        return $this->output([
            "date" => $errorLogRepository->getDate()
        ], Success::SEL_SUCCESS);
    }
}
