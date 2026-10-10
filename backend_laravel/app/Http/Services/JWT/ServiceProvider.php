<?php

declare(strict_types=1);

namespace App\Http\Services\JWT;
use App\Utils\JWT\Control;

class ServiceProvider{
  /**
     * @description:  SAAS后台管理员登录token
     * @return Control
     */
    public  function devLogin() :Control
    {
        //一个星期过期
        return new Control('admin_login', -1,7 * 24 * 3600 , 3600)  ;
    }

    /**
     * @description:  租户后他其管理员登录token
     * @return Control
     */
    public  function saasLogin() :Control
    {
        //一个星期过期
        return new Control('saas_login', -1,7 * 24 * 3600 , 3600)  ;
    }
}
