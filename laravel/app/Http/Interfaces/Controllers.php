<?php

declare(strict_types=1);

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use Closure;
use App\Helpers\Output\Interface\Json as Http;


interface Controllers
{
    public function __construct(Request $request, Closure|null $formatFunc = null, array $funcParams = [], string|null $method = null);

    public function output(array $data, Http $http, array $replace = [], array $header = []): void;


    public  function outputEnum(Http $http, array $replace = []): void;


    public  function outputList(int $total, array|object $list, array $addField = []): void;


    public  function filtrate(): array;
}
