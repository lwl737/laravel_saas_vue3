<?php
declare(strict_types=1);

namespace App\Common\Repository\Saas;
use App\Common\Repository\BaseRepository as Base;
use App\Services\Tenant\Server as TenantServer;

abstract class BaseRepository extends Base{

    public final function tenancyInitialize(int|string|null $tenant_id = null){
        if($tenant_id) TenantServer::tenancyInitialize($tenant_id);
        return $this;
    }

}
