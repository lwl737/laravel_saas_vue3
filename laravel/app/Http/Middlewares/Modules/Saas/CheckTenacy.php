<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Services\Tenant\Server;
use App\Helpers\Enums\HeaderReq;
use App\Helpers\Func;
use App\Helpers\Output\Json\Saas;
use App\Services\Saas\AdminsLoginInfo;
use App\Helpers\Inject;
use App\Helpers\Output\Json\Error;
use App\Dao\Mysql\Dev\SaasTenantDao\Orm as SaasTenantDao;
use App\Dao\Mysql\Dev\SaasTenantDao\Enums\TenantEnable as TenantEnable;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById;

class CheckTenacy
{
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $tenant_id = $request->header(HeaderReq::TENANT_ID->value, "");

        if (!preg_match('/(^[1-9][0-9]+$)|(^[1-9]$)/', (string)$tenant_id)) return Func::toHttpEnum(Saas::TENANT_ID_ERROR);

        $tenant_res = SaasTenantDao::static()->checkTenantEnable(["tenant_id" => $tenant_id]);

        if ($tenant_res instanceof TenantEnable) return match ($tenant_res) {
            //租户不存在
            TenantEnable::NOT_EXIST => Func::toHttpEnum(Saas::TENANT_ID_NOT_EXIST),
            //租户被禁用
            TenantEnable::STATUS_NOT_ENABLE => Func::toHttpEnum(Saas::TENANT_ID_DISABLE),
            default => Func::toHttpEnum(Saas::TENANT_ID_NOT_EXIST),
        };

        try {
            Server::tenancyInitialize($tenant_id);
        } catch (\Exception $e) {
            if ($e instanceof TenantCouldNotBeIdentifiedById) return Func::toHttpEnum(Saas::TENANT_ID_NOT_EXIST);
            else Func::toHttpEnum(Error::MESSAGE, ['message' => $e->getMessage()]);
        }

        Inject::bind(AdminsLoginInfo::class, new AdminsLoginInfo(tenant_id: (int)$tenant_id));

        return $next($request);
    }
}
