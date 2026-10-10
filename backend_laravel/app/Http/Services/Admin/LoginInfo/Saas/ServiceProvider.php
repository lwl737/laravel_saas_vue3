<?php

declare(strict_types=1);

namespace App\Http\Services\Admin\LoginInfo\Saas;
use App\Services\Admin\LoginInfo\BaseServiceProvider;
use App\Helpers\Enums\HeaderRes;
use App\Http\Services\JWT\ServiceProvider as JwtServiceProvider;

class ServiceProvider extends BaseServiceProvider{

    public int $tenant_id = 0;

    public function getTenantId(): int
    {
        return  $this->tenant_id;
    }

    public function setTenantId(int $tenant_id){
       $this->tenant_id = $tenant_id;
       return $this;
    }

    public function createToken(array $params): array
    {
        return [
            HeaderRes::SAAS_LOGIN_TOKEN->name => app()->make(JwtServiceProvider::class)->saasLogin()->create(
                [
                    'admins_id' => $params["admins_id"],
                    'password' => $params["password"],
                    'tenant_id' => $params["tenant_id"],
                    'username' =>   $params["username"]
                ]
            )
        ];
    }
}
