<?php

declare(strict_types=1);

namespace App\Http\Middlewares\Modules\Dev;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Func;
use App\Helpers\Output\Json\Referer;
use App\Http\Services\Referer\ServiceProvider as RefererServiceProvider;
use App\Utils\CheckReferer\{Status};

class CheckReferer
{

    public function __construct(
      public readonly  RefererServiceProvider $serviceProvider
    )
    {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next )
    {
        $status =   $this->serviceProvider->dev($request)->getStatus();
        return match ($status) {
            Status::SUCCESS => $next($request),
            Status::NOT_DEFIENED => Func::toHttpEnum(Referer::REQ_ILLEGAL),
            Status::ERROR => Func::toHttpEnum(Referer::REQ_ILLEGAL),
        };
    }
}
