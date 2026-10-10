<?php

declare(strict_types=1);
namespace App\Http\Interfaces;
use App\Http\Systems\CustomRouter;
/**
 * 最后一个中间件
 */
interface Entrances{

    public function before();

    public function after(CustomRouter $customRouter);

}
