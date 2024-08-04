<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
class SaasMenuAuth extends BaseDev{
     use Cachable;


     /**
     * 获取该权限的api
     */
    public function api()
    {
        return $this->hasMany(SaasMenuAuthApi::class,'auth_id','auth_id');
    }

    /**
     * 获取该权限的按钮
     */
    public function buttons()
    {
        return $this->hasMany(SaasMenuAuthButtons::class,'auth_id','auth_id');
    }
    /**
     * 获取该权限的页面
     */
    public function pages()
    {
        return $this->hasMany(SaasMenu::class,'auth_id','auth_id');
    }

}
