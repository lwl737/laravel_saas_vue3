<?php

declare(strict_types=1);

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use Closure;
use App\Helpers\Output\Json;

use App\Http\Systems\CustomRouter;


interface Controllers
{
    public function __construct(CustomRouter $customRouter);

}
