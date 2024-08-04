<?php

declare(strict_types=1);

namespace App\Http\Entrances\Modules;

use Illuminate\Http\Request;
use Closure;
use App\Helpers\Func;
use App\Helpers\Inject;
use App\Helpers\Output\Json;
use App\Helpers\Output\Json\Error;
use App\Http\Interfaces\Entrances;
use App\Helpers\Output\Json\Success;
class Base implements Entrances
{


    public function __construct(
        protected readonly Request $request,
        protected readonly array   $route_auto,
        protected readonly array   $parameters = [],
        protected readonly string  $output = "json"
    ) {
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



    public final  function handle(
        string|array $moduleClass,
        Closure|null $formatFunc = null,
        array $funcParams = [],
        \Closure|null $afterFunc = null,
        \Closure|null $resultAfter = null
    ) {
        $module = $this->newModule($moduleClass, $formatFunc, $funcParams);

        if ($afterFunc !== null) $afterFunc($module);  //完成操作后回调 可插入日志功能

        // var_dump($resultAfter);

        if($module->getLocation()){
            //返回的是重定向页面
            return  redirect($module->getLocation());
        }else{
            return match ($this->output) {
                "json" => $this->json($resultAfter ? $resultAfter($module->result, $this->output) : $module->result),
                "xml" =>  $this->xml($resultAfter ? $resultAfter($module->result, $this->output) : $module->result),
                // "inertia" => $this->inertia($resultAfter ? $resultAfter($module->result, $this->output) : $module->result),
                default => $this->json($resultAfter ? $resultAfter($module->result, $this->output) : $module->result),
            };
        }
    }


    public  function loadController(
        string|array $moduleClass,
        Closure|null $formatFunc = null,
        array $funcParams = []
    ) {
        return $this->handle($moduleClass, $formatFunc, $funcParams);
    }

    private  function newModule(
        string|array $moduleClass,
        Closure|null $formatFunc = null,
        array $funcParams = []
    ) {

        $params = [
            //判断前面是否已经实例化过了
            "result" => Inject::bindGet(Json::class, new Json( [] , Success::MODULE_NOT_DEFIND ) )  ,
            "formatFunc" => $formatFunc,
            "funcParams" => $funcParams,
            "reqFunc" => null,
            "route_auto" => $this->route_auto,
            "parameters" => $this->parameters,
        ];
        if (is_string($moduleClass)) return app()->make($moduleClass,  $params);
        else if (array_is_list($moduleClass) && count($moduleClass) === 2) {
            return app()->make($moduleClass[0], [...$params, "reqFunc" => $moduleClass[1]]);
        } else Func::throwHttpCustom(Error::MODULE_PARAMS_ERROR);  //填错参数
    }
}
