<?php

declare(strict_types=1);

namespace App\Http\Services\Admin\LoginInfo\Dev;

use App\Helpers\Enums\HeaderRes;
use App\Http\Services\JWT\ServiceProvider as JwtServiceProvider;
use App\Services\Admin\LoginInfo\BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function createToken(array $params): array
    {
        return [
            HeaderRes::DEV_LOGIN_TOKEN->name => app()->make(JwtServiceProvider::class)->devLogin()->create(
                [
                    'admins_id' => $params["admins_id"],
                    'password' => $params["password"],
                    'username' =>   $params["username"]
                ]
            )
        ];
    }
}
