<?php

declare(strict_types=1);

namespace App\Http\Services\Referer;
use App\Utils\CheckReferer\Control;
use App\Helpers\Enums\Referer;
class ServiceProvider{
    public  function dev(\Illuminate\Http\Request $request ){
        return  new Control($request, Referer::DEV);
    }
    public  function saas(\Illuminate\Http\Request $request ){
        return  new Control($request, Referer::SAAS);
    }
}
