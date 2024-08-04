<?php

declare(strict_types=1);

namespace App\Jobs;
use App\Services\Tenant\Tenant;
use App\Dao\Mysql\Dev\SaasTenantDao\Orm as SaasTenantDao;
class TenantCreate extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        [
            "tenant_id" => $tenant_id
        ] = $this->getData();

        //创建租户时会自动执行数据库迁移
        Tenant::create(['id' => (string)$tenant_id]);
        //修改创建中状态
        SaasTenantDao::static()->editCreating(["creating" => 1 ,  "tenant_id" => $tenant_id]);
    }
}
