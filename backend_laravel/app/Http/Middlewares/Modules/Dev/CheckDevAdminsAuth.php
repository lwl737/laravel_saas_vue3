<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use App\Helpers\Enums\HeaderReq;
use App\Helpers\Output\Json\Dev;
use App\Helpers\Func;
use App\Http\Services\Admin\LoginInfo\Dev\ServiceProvider as AdminsLoginInfo;
use Illuminate\Http\Request;
use Closure;
use App\Http\Services\Admin\Permissions\Dev\ServiceProvider as  Permissions;
use App\Http\Systems\CustomRouter;
class CheckDevAdminsAuth
{

    public function __construct(
        public readonly  AdminsLoginInfo $adminsLoginInfo,
        public readonly  Permissions $permissions,
    ) {}

    public string $prefix = 'dev';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next )
    {
        // $web_router_path = $request->header(HeaderReq::DEV_ROUTER_PATH->value , "");

        // app()->bind(CustomRouter::class,fn()=>new CustomRouter($request->path(),['test' => 123213]));

        $web_router_path = HeaderReq::DEV_ROUTER_PATH->get("");

        if(!$web_router_path) return Func::toHttpEnum(Dev::WEB_ROUTER_PATH_ERROR);  //没有传前端当前路由

        $starts = Func::startsWith(app(CustomRouter::class)->getPath() ,  $this->prefix ) ;

        if( !$starts ) return Func::toHttpEnum(Dev::PREFIX_ERROR);  //前缀对不上

        $api_path =  mb_substr(app(CustomRouter::class)->getPath() ,  mb_strlen($this->prefix) + 1 ) ; // 斜杠也算上+1然后截取 要检测的api

        $role_id = $this->adminsLoginInfo->getInfo(['role_id'])['role_id'];

        $result = $this->permissions->checkApi()->checkMenuApi( $role_id , $api_path ,  $web_router_path);
        //没定义该api就返回null
        if( $result === null )  return $next($request);

        $result['router_path'] = $web_router_path;

        [
            "check" => $check ,            //检查api是否有权限 true false
            "auth_name" => $auth_name ,    //权限名称
            "menu_title" => $menu_title ,  //栏目名称
            // "menu_id" => $menu_id
            // "add_log" => $add_log    //是否加入操作日志 1加入日志 0不加入
        ] =  $result;

        if(!$check)  return Func::toHttpEnum(http:Dev::NOT_AUTH , replace:[ 'menu_title' => $menu_title , 'auth_name' => $auth_name ]);

        $this->adminsLoginInfo->setAuthApi( $result ) ;

        return $next($request);
    }

}
