<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Interfaces\Controllers;
use Closure;
use App\Helpers\{
    Enums\Attributes,
    Func,
};
use App\Helpers\Output\Json\Success;
use App\Helpers\Output\Interface\Json as JsonInterface;
use App\Helpers\Output\Json;

abstract class BaseController implements Controllers
{
    /*请求数据*/
    public readonly array  $params;          //请求参数
    public readonly array  $loginInfo;       //请求的token信息
    public readonly string $reqFunc;         //请求方法

    public readonly array $parameters;       //斜杠的路由参数

    public readonly array $file;       //文件
    /**/

    public function __construct(
        Request $request,
        Closure|null $formatFunc = null,
        array $funcParams = [],
        string|null $reqFunc = null,
        array $route_auto = [],
        array $parameters = [],
        Json|null $result = null
    ) {

        $this->result =  $result ? $result  :  new Json([], Success::MODULE_NOT_DEFIND); //中间件那里设置的响应头信息

        $reqFunc = $reqFunc === null ? $route_auto["key"] : $reqFunc;

        $this->reqFunc = $reqFunc;

        $this->parameters = $parameters;

        $this->params = $formatFunc === null  ?   $request->input() : $formatFunc($request->input());

        $this->loginInfo  = $request->get(Attributes::LOGIN_INFO->value, []);  //中间件那里解析到的获取登录信息

        $this->result->addHeader($request->get(Attributes::RES_HEADER->value, [])); //中间件那里设置的响应头信息

        $this->file = $request->file();

        return $this->$reqFunc(...$funcParams);
    }

    /*返回数据*/
    public readonly Json $result;
    private string $location = "";
    /**/


    /**
     * @description:   输出信息
     * @param {array} $data
     * @param {Http} $http
     * @param {array} $replace
     * @param {array} $header
     * @return {*}
     */
    public final function output(array $data, JsonInterface $http, array $replace = [], array $header = []): void
    {

        $this->result->addHeader($header);
        $this->result->setData($data);
         $this->result->setHttp($http, $replace);
    }

    /**
     * @description:   输出信息
     * @param JsonInterface|PagesInterface $enum  返回页面或者数据
     * @param array       $data     返回的data数据
     * @param array|null  $replace  替换的信息
     * @param array       $header   头部信息
     * @return void
     */
    public final function outputEnum(JsonInterface $enum , array|null $replace = null , array $data = [] ,array $header = []): void
    {

            $this->result->setHttp($enum, $replace);
            $this->result->addHeader($header);
            $this->result->setData($data);

    }




    /**
     * @description:  输出列表信息
     * @param {*} $list
     * @param {*} $total
     * @return {*}
     */
    public final function outputList(int $total, array|object $list, array $addField = []): void
    {

        $this->output(
            $this->formatList($total, $list, $addField),
            Success::SEL_SUCCESS
        );
    }

    public final function formatList(int $total, array|object $list, array $addField = [])
    {
        return array_merge([
            'list' => $list,
            'pageNum'  => (int)$this->params['pageNum'],
            'pageSize'  => (int)$this->params['pageSize'],
            'total' => $total
        ], $addField);
    }


    public final function filtrate(): array
    {
        return empty($this->params) ? [] : array_filter($this->params, Func::filtrfunction(...));
    }

    protected final function location(string $url){
        $this->location = $url;
    }

    public final function getLocation(){
        return $this->location;
    }


}
