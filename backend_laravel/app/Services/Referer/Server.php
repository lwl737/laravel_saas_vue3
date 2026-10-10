<?php

declare(strict_types=1);

namespace App\Services\Referer;
use App\Utils\CheckReferer\Control;
use App\Helpers\Enums\Referer;
class Server{
    public static function dev(\Illuminate\Http\Request $request ){
        return  new Control($request, Referer::DEV);
    }
    public static function saas(\Illuminate\Http\Request $request ){
        return  new Control($request, Referer::SAAS);
    }
}
