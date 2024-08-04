<?php

declare(strict_types=1);

namespace App\Http\Routes\Modules\Saas;

use App\Http\Interfaces\Routes;
use App\Rules\{NotZero, HaveZero};


class Organi implements Routes
{
    public function routes(): array
    {
        return [
            'add' =>  ["method" => "post", "format" => function ($params) {
                $params['organi_pid'] = (int)$params['organi_pid'];
                return $params;
            }, "valid" => ["organi_name", "organi_sort", "organi_pid"]],
            'edit' => ["method" => "put", "valid" => ["organi_name", "organi_sort", "organi_id"]],
            'del' =>  ["method" => "delete", "valid" => ["organi_id"]],
            'all' =>  ["method" => "get"],
        ];
    }


    public function rules(): array
    {
        return  [
            'organi_name' => ['required', 'string', 'max:50'],
            'organi_sort' => ['required', new HaveZero(999999)],
            'organi_id' => ['required', new NotZero],
            'organi_pid' => ['present', new HaveZero]
        ];
    }
}
