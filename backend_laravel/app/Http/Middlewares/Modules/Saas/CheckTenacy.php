<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Services\Tenant\Server;
use App\Helpers\Enums\HeaderReq;
use App\Helpers\Func;
use App\Helpers\Output\Json\Saas;
use App\Helpers\Output\Json\Error;
use App\Common\Dao\Mysql\Dev\SaasTenantDao\Enums\TenantEnable;
use App\Common\Repository\Dev\SaasTenantRepository;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById;
use App\Http\Services\Admin\LoginInfo\Saas\ServiceProvider as AdminsLoginInfo;

class CheckTenacy
{
    public readonly  AdminsLoginInfo $adminsLoginInfo;
    public function __construct(
        AdminsLoginInfo $adminsLoginInfo,
        public readonly  SaasTenantRepository $saasTenantRepository
    ) {
        $this->adminsLoginInfo = app()->instance(AdminsLoginInfo::class, $adminsLoginInfo);
    }
    public function handle(\Illuminate\Http\Request $request, \Closure $next)
    {
        $tenant_id = $request->header(HeaderReq::TENANT_ID->value, "");

        if (!preg_match('/(^[1-9][0-9]+$)|(^[1-9]$)/', (string)$tenant_id)) return Func::toHttpEnum(Saas::TENANT_ID_ERROR);

        $tenant_res = $this->saasTenantRepository->checkTenantEnable(["tenant_id" => $tenant_id]);

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
            else return Func::toHttpEnum(Error::MESSAGE, ['message' => $e->getMessage()]);
        }

        $this->adminsLoginInfo->setTenantId((int)$tenant_id);
        return $next($request);
    }
}
