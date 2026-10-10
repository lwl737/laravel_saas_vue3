<?php

declare(strict_types=1);

namespace App\Common\Dao\Mysql\Saas;

use  App\Common\Dao\Mysql\BaseMysql;
use App\Services\Tenant\Server as TenantServer;

abstract class BaseSaas extends BaseMysql
{

    public function tenancyInitialize(int|string|null $tenant_id = null){
        if($tenant_id) TenantServer::tenancyInitialize($tenant_id);
        return $this;
    }




}
