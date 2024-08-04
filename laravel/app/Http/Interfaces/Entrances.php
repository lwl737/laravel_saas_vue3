<?php

declare(strict_types=1);
namespace App\Http\Interfaces;
interface Entrances{

    public function loadController(string|array $moduleClass, \Closure|null $formatFunc = null, array $funcParams = []);
}
