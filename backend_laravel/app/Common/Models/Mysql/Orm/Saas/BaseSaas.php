<?php

declare(strict_types=1);
namespace App\Common\Models\Mysql\Orm\Saas;
use App\Common\Models\Mysql\Orm\BaseOrm;
use App\Http\Services\Admin\LoginInfo\Saas\ServiceProvider;

abstract class BaseSaas extends BaseOrm{

    protected static function boot()
    {
        parent::boot();
    }


    /**
     * 对应哪个登录容器
     * @return string
     */
    public function getLoginInfo():string{
        return  ServiceProvider::class;
     }

}
