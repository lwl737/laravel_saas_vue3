<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
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
use App\Http\Systems\CustomRouter;

abstract class BaseController  implements Controllers
{
    /*请求数据*/
    protected readonly array $params;          //请求参数
    protected readonly array $parameters;       //斜杠的路由参数
    protected readonly array $file;             //文件
    /**/




    public function __construct(
        private CustomRouter $customRouter
    ) {

        $this->parameters = $this->customRouter->getParameter();

        $this->params = $this->customRouter->getParams();

        $this->file = $this->customRouter->getFile();
    }




    /**
     * @description:   输出信息
     * @param {array} $data
     * @param {Http} $http
     * @param {array} $replace
     * @param {array} $header
     * @return {*}
     */
    protected final function output(array $data, JsonInterface $http, array $replace = [], array $header = []): void
    {

        $this->customRouter->getResult()->addHeader($header);
        $this->customRouter->getResult()->setData($data);
        $this->customRouter->getResult()->setHttp($http, $replace);
    }

    /**
     * @description:   输出信息
     * @param JsonInterface|PagesInterface $enum  返回页面或者数据
     * @param array       $data     返回的data数据
     * @param array|null  $replace  替换的信息
     * @param array       $header   头部信息
     * @return void
     */
    protected final function outputEnum(JsonInterface $enum, array|null $replace = null, array $data = [], array $header = []): void
    {

        $this->customRouter->getResult()->setHttp($enum, $replace);
        $this->customRouter->getResult()->addHeader($header);
        $this->customRouter->getResult()->setData($data);
    }




    /**
     * @description:  输出列表信息
     * @param {*} $list
     * @param {*} $total
     * @return {*}
     */
    protected final function outputList(int $total, array|object $list, array $addField = []): void
    {

        $this->output(
            $this->formatList($total, $list, $addField),
            Success::SEL_SUCCESS
        );
    }

    protected final function formatList(int $total, array|object $list, array $addField = [])
    {
        return array_merge([
            'list' => $list,
            'pageNum'  => (int)$this->params['pageNum'],
            'pageSize'  => (int)$this->params['pageSize'],
            'total' => $total
        ], $addField);
    }


    protected final function filtrate(): array
    {
        return empty($this->params) ? [] : array_filter($this->params, Func::filtrfunction(...));
    }

    protected final function location(string $url)
    {
        $this->customRouter->setLocation($url);
    }


    protected final function params(array|null $field = null)
    {
        if (!$field) return  $this->params;
        $params = [];
        foreach ($field as $val) {
            if (is_string($val)) {
                if (array_key_exists($val, $this->params)) $params[$val] = $this->params[$val];
            } else if (array_is_list($val) &&  array_key_exists($val[0], $this->params)) {
                if (array_key_exists($val[0], $this->params)) $params[$val[0]]  =  $this->params[$val[0]]    ??  count($val) >= 2 ? $val[1] : null;
            }
        }
        return $params;
    }

    protected final function param(string $key, mixed $default = null)
    {
        return $this->params[$key] ?? $default;
    }
}
