<?php

declare(strict_types=1);
namespace App\Http\Interfaces;
interface Routes{

    public function routes():array;
    public function rules():array;
    // public array $rule;
}
