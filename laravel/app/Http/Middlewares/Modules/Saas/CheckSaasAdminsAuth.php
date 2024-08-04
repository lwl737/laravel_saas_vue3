<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Saas;

use App\Helpers\Enums\HeaderReq;
use App\Helpers\Output\Json\Saas;
use App\Helpers\Func;
use App\Services\Saas\AdminsLoginInfo;
use App\Helpers\Inject;
use Illuminate\Http\Request;
use Closure;
use App\Services\Saas\Permissions;
class CheckSaasAdminsAuth
{

    public string $prefix = 'saas';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next )
    {
        $web_router_path = $request->header(HeaderReq::SAAS_ROUTER_PATH->value , "");

        if(!$web_router_path) return Func::toHttpEnum(Saas::WEB_ROUTER_PATH_ERROR);  //没有传前端当前路由

        $starts = Func::startsWith($request->path() ,  $this->prefix ) ;

        if( !$starts ) return Func::toHttpEnum(Saas::PREFIX_ERROR);  //前缀对不上

        $api_path =  mb_substr( $request->path() ,  mb_strlen($this->prefix) + 1 ) ; // 斜杠也算上+1然后截取 要检测的api

        $adminsLoginInfo = Inject::get(AdminsLoginInfo::class);

        $role_id = $adminsLoginInfo->getInfo(['role_id'])['role_id'];

        $result = Permissions::checkApi()->checkMenuApi( $role_id , $api_path ,  $web_router_path);
        //没定义该api就返回null
        if( $result === null )  return $next($request);

        $result['router_path'] = $web_router_path;

        [
            "check" => $check ,    //检查api是否有权限 true false
            "auth_name" => $auth_name ,    //权限名称
            "menu_title" => $menu_title ,  //栏目名称
            "menu_id" => $menu_id
            // "add_log" => $add_log    //是否加入操作日志 1加入日志 0不加入
        ] =  $result;

        if(!$check)  return Func::toHttpEnum(http:Saas::NOT_AUTH , replace:[ 'menu_title' => $menu_title , 'auth_name' => $auth_name ]);

        Inject::bind( AdminsLoginInfo::class ,  $adminsLoginInfo->setAuthApi( $result )) ;

        return $next($request);
    }

}
