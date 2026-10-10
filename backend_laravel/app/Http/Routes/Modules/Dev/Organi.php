<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Dev;

use App\Http\Interfaces\Routes;
use App\Rules\{NotZero, HaveZero , NotHas};


class Organi implements Routes
{
    public function routes(): array
    {
        return [
            'add' =>  ["method" => "post", "format" => function ($params) {
                $params['organi_pid'] = (int)$params['organi_pid'];
                return $params;
            }, "valid" => ["organi_name", "organi_sort", "organi_pid"]],
            'edit' => ["method" => "put", "parameters" => ['organi_id'] , "valid" => ["organi_name", "organi_sort"]],
            'del' =>  ["method" => "delete", "valid" => ["organi_id"]],
            'all' =>  ["method" => "get"],
            'option' =>  ["method" => "get"],
            'homeOption' =>  ["method" => "get"],
        ];
    }


    public function rules(): array
    {
        return  [
            'organi_name' => ['required', 'string', 'max:50' , new NotHas([" "]) ],
            'organi_sort' => ['required', new HaveZero ],
            'organi_id' => ['required', new NotZero],
            'organi_pid' => ['present', new HaveZero]
        ];
    }
}
