<?php

declare(strict_types=1);
namespace App\Http\Interfaces;
use Illuminate\Http\Request;

interface Middlewares{

    public function handle(Request $request, \Closure $next):\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse;

}
