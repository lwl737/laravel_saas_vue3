<?php

declare(strict_types=1);

namespace App\Http\Systems;

use App\Helpers\Func;
use Illuminate\Http\Request;
use App\Helpers\Output\Json;

/**
 *  获取路由信息
 */
class CustomRouter
{

    protected readonly Json $result;
    private string $location = "";
    private string $path;
    private array  $info;
    private array  $params;
    private array  $parameter;
    private bool   $is_init = false;
    public function __construct(

        public readonly  Request  $request,

    ) {
        $this->result = new Json();
    }

    public function init(
        string $path,
        array  $info,
        array  $params,
        array  $parameter,
    ) {
        if (!$this->is_init) {
            $this->path = $path;
            $this->info = $info;
            $this->params = $params;
            $this->parameter = $parameter;
            $this->is_init = true;
        }
    }

    public function getIsInit()
    {
        return  $this->is_init;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setLocation(string $location)
    {
        $this->location =  $location;
    }
    public function getLocation()
    {
        return  $this->location;
    }

    public function getInfo(string|null $key = null, mixed $default = null): mixed
    {
        return $key ? $this->info["key"] ?? $default : $this->info;
    }
    public function getParameter(): array
    {
        return $this->parameter;
    }
    public function getParams(array|null $field = null): array
    {
        $params = isset($this->info["format"]) ? $this->info["format"]($this->params) : $this->params;
        return  $field ? Func::formExtractData($params, $field) : $params;
    }

    public function getFile(): array
    {
        return $this->request->file();
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResult(): Json
    {
        return $this->result;
    }
}
