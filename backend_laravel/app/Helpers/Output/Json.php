<?php

declare(strict_types=1);

namespace App\Helpers\Output;

use App\Helpers\Output\Interface\Json as Http;
use App\Helpers\Output\Json\Success;
//内部状态码错误
class Json
{

    /**
      *  @param mixed $data 返回的数据
      *  @param Http|Inertia  $http 返回的信息
      *  @param array $header   响应头信息
      *  @param array \App\Helpers\Enums::Header[] $replace  需要替换的字段
      *  @param \App\Helpers\Enums\TrueCode $trueCode 网页状态码
      **/
    public function __construct(
        private   mixed    $data = [],
        private   Http     $http = Success::NOT_DEFIND,
        private   array    $header = [],
        private   array    $replace = []
    ) {
    }


    public function getMessage(): string
    {
        $msg = $this->http->text();
        foreach ($this->replace as $key => $val) $msg = str_replace('{' . $key . '}', (string)$val, (string)$msg);
        return $msg;
    }

    public function getReplace():array{
         return $this->replace;
    }

    public function getHttpVal(): int
    {
        return $this->http->errorCode();
    }

    public function getTrueCodeVal(): int
    {
        return $this->http->trueCode()->value;
    }

    public function getHttpName(): string
    {
        return $this->http->name;
    }

    public function getHttpEnum(): Http
    {
        return $this->http;
    }

    public function getTrueCodeName(): string
    {
        return $this->http->trueCode()->name;
    }


    public function getHeader(): array
    {
        return $this->header;
    }


    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): array
    {
        return $this->data = $data;
    }



    public function addHeader(array $header , bool $reverse = false )
    {
        if(!$reverse)  $this->header = array_merge($this->header,$header);
        else $this->header = array_merge($header , $this->header);
    }

    public function setHttp(Http $http,array|null $replace = null)
    {
        $this->http =  $http;
        if($replace) $this->replace =  $replace;
    }


}
