<?php

namespace App\Services\Tenant;



class Server
{
    public static function tenancyInitialize(string|int $tenancy)
    {
        //切库操作
        return app(\Stancl\Tenancy\Tenancy::class)->initialize($tenancy);
    }

    public static function tenancyId():string|int|null{
       return tenant('id');
    }
}
