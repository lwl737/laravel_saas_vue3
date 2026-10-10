<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Es;

use App\Http\Interfaces\Routes;


class Test implements Routes
{
    public function routes(): array
    {
        return [
            'add' => ["method" => "get"],
            'get' => ["method" => "get"],
        ];
    }


    public function rules(): array
    {
        return  [];
    }
}
