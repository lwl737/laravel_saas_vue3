<?php

declare(strict_types=1);
namespace App\Models\Mysql\Orm\Dev;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
class MenuAuth extends BaseDev{
     use Cachable;


     /**
     * 获取该权限的api
     */
    public function api()
    {
        return $this->hasMany(MenuAuthApi::class,'auth_id','auth_id');
    }

    /**
     * 获取该权限的按钮
     */
    public function buttons()
    {
        return $this->hasMany(MenuAuthButtons::class,'auth_id','auth_id');
    }
    /**
     * 获取该权限的页面
     */
    public function pages()
    {
        return $this->hasMany(Menu::class,'auth_id','auth_id');
    }

}
