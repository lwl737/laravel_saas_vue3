<?php
declare(strict_types=1);

namespace App\Http\Systems;

use Illuminate\Http\Request;
use Closure;
use App\Helpers\Output\Json;
use App\Helpers\Func;
use App\Http\Systems\CustomRouter;
use App\Http\Interfaces\Entrances;
use Illuminate\Http\JsonResponse;

class EndMiddleware
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $entranceClass)
    {
         /** @var Entrances|null **/
         $entranceClass = $entranceClass ? new $entranceClass : null;
         $entranceClass?->before();
         $res = $next($request) ;
         if($res instanceof JsonResponse){
            // 错误信息 json_decode( $res->getContent() , true)
            return $res;
         }
         $entranceClass?->after(app(CustomRouter::class));
         return $this->output();
    }



    /**
     * @description:
     * @param {string} $msg    返回信息
     * @param {array} $data    数据
     * @param {array} $header  响应头
     * @return {*}
     */
    private function json(Json  $result)
    {
        return  Func::toHttpJson($result);
    }

    private function xml(Json  $result)
    {
        return   Func::toHttpXml($result);
    }


    // private function inertia(Pages  $result)
    // {
    //     return   Func::toInertia($result , $this->request);
    // }

    private final  function output() {
        if(app(CustomRouter::class)->getLocation()){
            //返回的是重定向页面
            return  redirect(app(CustomRouter::class)->getLocation());
        }else{
            return match ( app(CustomRouter::class)->getInfo("output","json")  ) {
                "json" => $this->json(app(CustomRouter::class)->getResult()),
                "xml" =>  $this->xml(app(CustomRouter::class)->getResult()),
                default => $this->json(app(CustomRouter::class)->getResult()),
            };
        }
    }
}
