<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas;
use App\Dao\Mysql\Dev\SaasTenantDao\Orm as SaasTenantDao;
use App\Helpers\Output\Json\Success;
use App\Helpers\Queue\Modules\Rabbitmq;
use App\Helpers\Func;

use App\Common\Repository\Dev\SaasTenantRepository;

class Tenant extends BaseController
{

    public function list(
        SaasTenantRepository $saasTenantRepository
    )
    {
        ["total" => $total , "list" => $list] = $saasTenantRepository->list($this->params);

        return $this->outputList(
            total: $total,
            list: $list
        );
    }
    public function add(
        SaasTenantRepository $saasTenantRepository
    )
    {
        $saasTenantRepository->add($this->params);

        return $this->outputEnum(Success::ADD_SUCCESS);
    }
    public function edit(
        int $tenant_id,
        SaasTenantRepository $saasTenantRepository
    )
    {
        $saasTenantRepository->edit($tenant_id , $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    public function editStatus(
        int $tenant_id,
        SaasTenantRepository $saasTenantRepository
    )
    {
        $saasTenantRepository->editStatus($tenant_id , $this->params);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }
    public function del(
        SaasTenantRepository $saasTenantRepository
    )
    {
        $saasTenantRepository->del($this->params["ids"]);
        return $this->outputEnum(Success::EDIT_SUCCESS);
    }

    public function all(
        SaasTenantRepository $saasTenantRepository
    )
    {
        return $this->output(
            ["all" => $saasTenantRepository->all()],
            Success::SEL_SUCCESS
        );
    }
}
